<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\LaporanMasalahAir;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    /**
     * PBI-07: Menampilkan daftar laporan yang menunggu verifikasi (GET /reports/verification)
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'Menunggu');
        $query = LaporanMasalahAir::with(['pelapor', 'titikAir', 'petugas', 'adminVerifikator']);

        if ($status && $status !== 'all') {
            $query->where('status_laporan', $status);
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('kode_laporan', 'like', "%{$search}%")
                  ->orWhere('kategori_masalah', 'like', "%{$search}%")
                  ->orWhere('wilayah', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhereHas('pelapor', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->orderBy('tanggal_laporan', 'desc')->get();

        // Data petugas aktif untuk dropdown penugasan (UC-11)
        $petugasAktif = User::where('role', 'petugas')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Semua petugas termasuk nonaktif untuk testing validasi Alur Alternatif A2
        $semuaPetugas = User::where('role', 'petugas')
            ->orderBy('name')
            ->get();

        // Hitung statistik untuk tab filter
        $counts = [
            'Menunggu' => LaporanMasalahAir::where('status_laporan', 'Menunggu')->count(),
            'Ditugaskan' => LaporanMasalahAir::where('status_laporan', 'Ditugaskan')->count(),
            'Diproses' => LaporanMasalahAir::where('status_laporan', 'Diproses')->count(),
            'Selesai' => LaporanMasalahAir::where('status_laporan', 'Selesai')->count(),
            'Ditolak' => LaporanMasalahAir::where('status_laporan', 'Ditolak')->count(),
            'all' => LaporanMasalahAir::count(),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $reports,
                'counts' => $counts,
            ]);
        }

        return view('reports.verification', compact('reports', 'petugasAktif', 'semuaPetugas', 'counts', 'status'));
    }

    /**
     * Detail satu laporan
     */
    public function show($id, Request $request)
    {
        $report = LaporanMasalahAir::with(['pelapor', 'titikAir', 'petugas', 'adminVerifikator', 'riwayatPenanganan'])
            ->where('laporan_id', $id)
            ->orWhere('kode_laporan', $id)
            ->firstOrFail();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $report,
            ]);
        }

        return view('reports.show', compact('report'));
    }

    /**
     * PBI-07: Memverifikasi (terima / tolak) dan menugaskan laporan (PATCH /reports/:id/verification)
     */
    public function update(Request $request, $id)
    {
        // Cari laporan berdasarkan primary key atau kode laporan
        $report = LaporanMasalahAir::where('laporan_id', $id)
            ->orWhere('kode_laporan', $id)
            ->firstOrFail();

        // Validasi input sesuai spesifikasi PBI-07
        $validated = $request->validate([
            'keputusan' => 'required|in:diterima,ditolak',
            'alasan_penolakan' => 'required_if:keputusan,ditolak|nullable|string|max:1000',
            'officer_id' => 'required_if:keputusan,diterima|nullable|exists:users,id',
            'prioritas' => 'nullable|in:Tinggi,Sedang,Rendah',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'keputusan.required' => 'Keputusan verifikasi (terima atau tolak) wajib dipilih.',
            'keputusan.in' => 'Keputusan harus berupa diterima atau ditolak.',
            'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi bila laporan ditolak.',
            'officer_id.required_if' => 'Petugas lapangan wajib dipilih jika laporan diterima.',
            'officer_id.exists' => 'Petugas yang dipilih tidak ditemukan.',
        ]);

        $adminUser = Auth::user() ?? User::where('role', 'admin')->first();
        $adminId = $adminUser ? $adminUser->id : 1;

        if ($validated['keputusan'] === 'diterima') {
            // Validasi UC-11 Alur Alternatif A2: Terima tanpa memilih petugas atau petugas nonaktif ditolak
            $officer = User::find($validated['officer_id']);

            if (!$officer || $officer->role !== 'petugas') {
                throw ValidationException::withMessages([
                    'officer_id' => ['Pengguna yang dipilih bukan petugas lapangan yang sah.'],
                ]);
            }

            if (!$officer->is_active) {
                throw ValidationException::withMessages([
                    'officer_id' => ['Petugas yang dipilih sedang nonaktif. Silakan pilih petugas yang aktif.'],
                ]);
            }

            // Update status dan simpan penugasan
            $report->status_laporan = 'Ditugaskan';
            $report->admin_verifikator_id = $adminId;
            $report->petugas_id = $officer->id;
            $report->tanggal_ditugaskan = now();

            if (!empty($validated['prioritas'])) {
                $report->prioritas = $validated['prioritas'];
            } elseif (empty($report->prioritas)) {
                $report->prioritas = 'Sedang'; // Default prioritas
            }

            if (!empty($validated['catatan'])) {
                $report->catatan_verifikasi = $validated['catatan'];
            }

            $report->save();

            // PBI-17: Catat ke Audit Log
            AuditLog::createAuditLog(
                $adminId,
                'Verifikasi Laporan Diterima',
                'laporan_masalah_air',
                $report->kode_laporan,
                "Laporan {$report->kode_laporan} diterima dan ditugaskan kepada petugas {$officer->name} dengan prioritas {$report->prioritas}."
            );

            $message = "Laporan {$report->kode_laporan} berhasil diverifikasi dan ditugaskan kepada petugas {$officer->name}.";

        } else {
            // Keputusan: Ditolak (UC-11 Alur Alternatif A1: Wajib mengisi alasan penolakan)
            if (empty(trim($validated['alasan_penolakan'] ?? ''))) {
                throw ValidationException::withMessages([
                    'alasan_penolakan' => ['Alasan penolakan wajib diisi.'],
                ]);
            }

            $report->status_laporan = 'Ditolak';
            $report->admin_verifikator_id = $adminId;
            $report->petugas_id = null;
            $report->catatan_verifikasi = $validated['alasan_penolakan'];
            $report->save();

            // PBI-17: Catat ke Audit Log
            AuditLog::createAuditLog(
                $adminId,
                'Verifikasi Laporan Ditolak',
                'laporan_masalah_air',
                $report->kode_laporan,
                "Laporan {$report->kode_laporan} ditolak. Alasan: {$validated['alasan_penolakan']}"
            );

            $message = "Laporan {$report->kode_laporan} telah ditolak. Alasan penolakan telah tercatat.";
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $report->fresh(['pelapor', 'titikAir', 'petugas', 'adminVerifikator']),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}

