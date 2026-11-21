<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\JadwalPemeriksaan;
use App\Models\TitikSumberAir;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InspectionController extends Controller
{
    /**
     * PBI-09: Daftar jadwal pemeriksaan rutin (GET /inspections)
     * Admin melihat semua jadwal; Petugas hanya melihat miliknya (UC-14).
     */
    public function index(Request $request)
    {
        $user = Auth::user() ?? User::where('role', 'admin')->first();
        $isPetugasOnly = $user && $user->role === 'petugas';

        $query = JadwalPemeriksaan::with(['titikAir', 'petugas']);

        // Jika login sebagai petugas, batasi hanya jadwal miliknya (UC-14)
        if ($isPetugasOnly) {
            $query->where('petugas_id', $user->id);
        } elseif ($request->filled('officer_id')) {
            $query->where('petugas_id', $request->query('officer_id'));
        }

        if ($request->filled('water_point_id')) {
            $query->where('titik_air_id', $request->query('water_point_id'));
        }

        if ($request->filled('status') && $request->query('status') !== 'all') {
            $query->where('status_pemeriksaan', $request->query('status'));
        }

        if ($request->filled('from')) {
            $query->whereDate('tanggal_jadwal', '>=', $request->query('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('tanggal_jadwal', '<=', $request->query('to'));
        }

        $inspections = $query->orderBy('tanggal_jadwal', 'asc')->get();

        // Data pendukung untuk formulir
        $titikAirList = TitikSumberAir::orderBy('nama')->get();
        $petugasAktif = User::where('role', 'petugas')->where('is_active', true)->orderBy('name')->get();
        $semuaPetugas = User::where('role', 'petugas')->orderBy('name')->get();

        // Ringkasan status
        $countsQuery = JadwalPemeriksaan::query();
        if ($isPetugasOnly) {
            $countsQuery->where('petugas_id', $user->id);
        }
        $counts = [
            'dijadwalkan' => (clone $countsQuery)->where('status_pemeriksaan', 'dijadwalkan')->count(),
            'selesai' => (clone $countsQuery)->where('status_pemeriksaan', 'selesai')->count(),
            'dibatalkan' => (clone $countsQuery)->where('status_pemeriksaan', 'dibatalkan')->count(),
            'all' => (clone $countsQuery)->count(),
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $inspections->map(function ($item) {
                    return [
                        'id' => $item->jadwal_id,
                        'water_point_id' => $item->titik_air_id,
                        'officer_id' => $item->petugas_id,
                        'tanggal_jadwal' => $item->tanggal_jadwal->toIso8601String(),
                        'durasi_menit' => $item->durasi_menit,
                        'status' => $item->status_pemeriksaan,
                        'catatan' => $item->catatan,
                        'titik_air' => $item->titikAir,
                        'petugas' => $item->petugas,
                    ];
                }),
                'counts' => $counts,
            ]);
        }

        return view('inspections.index', compact(
            'inspections',
            'titikAirList',
            'petugasAktif',
            'semuaPetugas',
            'counts',
            'user',
            'isPetugasOnly'
        ));
    }

    /**
     * PBI-09: Buat jadwal pemeriksaan rutin (POST /inspections)
     * Aktor: Admin (UC-13)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'water_point_id' => 'required|exists:titik_sumber_air,titik_air_id',
            'officer_id' => 'required|exists:users,id',
            'tanggal_jadwal' => 'required|date',
            'durasi_menit' => 'required|integer|min:15|max:480',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'water_point_id.required' => 'Titik sumber air wajib dipilih.',
            'officer_id.required' => 'Petugas lapangan wajib dipilih.',
            'tanggal_jadwal.required' => 'Tanggal dan jam mulai jadwal wajib diisi.',
            'durasi_menit.required' => 'Durasi pemeriksaan wajib diisi (minimal 15 menit).',
        ]);

        $tanggalMulai = Carbon::parse($validated['tanggal_jadwal']);
        $durasiMenit = (int) $validated['durasi_menit'];
        $tanggalSelesai = $tanggalMulai->copy()->addMinutes($durasiMenit);

        // 1. Validasi UC-13 A2: Tanggal sudah lewat ditolak
        if ($tanggalMulai->isPast()) {
            throw ValidationException::withMessages([
                'tanggal_jadwal' => ['Jadwal pemeriksaan tidak boleh berada di waktu lampau.'],
            ]);
        }

        // 2. Validasi UC-13 A2: Petugas nonaktif ditolak
        $officer = User::find($validated['officer_id']);
        if (!$officer || $officer->role !== 'petugas' || !$officer->is_active) {
            throw ValidationException::withMessages([
                'officer_id' => ['Petugas yang dipilih sedang nonaktif atau tidak valid. Silakan pilih petugas aktif.'],
            ]);
        }

        // 3. Validasi UC-13 A1: Pemeriksaan bentrok (Overlap detection)
        $bentrok = $this->detectCollision(
            $validated['officer_id'],
            $validated['water_point_id'],
            $tanggalMulai,
            $durasiMenit
        );

        if ($bentrok) {
            $pesanBentrok = $bentrok['alasan'];
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $pesanBentrok,
                    'conflict' => $bentrok['conflict_schedule'],
                ], 409);
            }

            return redirect()->back()->withInput()->withErrors([
                'bentrok' => $pesanBentrok,
            ]);
        }

        // Simpan jadwal baru
        $jadwal = JadwalPemeriksaan::create([
            'titik_air_id' => $validated['water_point_id'],
            'petugas_id' => $validated['officer_id'],
            'tanggal_jadwal' => $tanggalMulai,
            'durasi_menit' => $durasiMenit,
            'status_pemeriksaan' => 'dijadwalkan',
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // Catat Audit Log
        $adminUser = Auth::user();
        AuditLog::createAuditLog(
            $adminUser ? $adminUser->id : 1,
            'Membuat Jadwal Pemeriksaan',
            'jadwal_pemeriksaan',
            $jadwal->jadwal_id,
            "Jadwal pemeriksaan rutin dibuat untuk petugas {$officer->name} di {$jadwal->titikAir->nama} pada {$tanggalMulai->format('d M Y H:i')}."
        );

        $successMsg = "Jadwal pemeriksaan untuk {$officer->name} di {$jadwal->titikAir->nama} berhasil dibuat tanpa bentrok.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'data' => $jadwal->fresh(['titikAir', 'petugas']),
            ], 201);
        }

        return redirect()->route('inspections.index')->with('success', $successMsg);
    }

    /**
     * PBI-09: Pindahkan atau ubah petugas dan durasi jadwal (PATCH /inspections/:id)
     * Aktor: Admin (UC-13 A3)
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalPemeriksaan::findOrFail($id);

        $validated = $request->validate([
            'officer_id' => 'required|exists:users,id',
            'tanggal_jadwal' => 'required|date',
            'durasi_menit' => 'required|integer|min:15|max:480',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $tanggalMulai = Carbon::parse($validated['tanggal_jadwal']);
        $durasiMenit = (int) $validated['durasi_menit'];

        // 1. Validasi UC-13: Tanggal lampau
        if ($tanggalMulai->isPast()) {
            throw ValidationException::withMessages([
                'tanggal_jadwal' => ['Jadwal pemeriksaan tidak boleh dipindahkan ke waktu lampau.'],
            ]);
        }

        // 2. Validasi UC-13: Petugas aktif
        $officer = User::find($validated['officer_id']);
        if (!$officer || $officer->role !== 'petugas' || !$officer->is_active) {
            throw ValidationException::withMessages([
                'officer_id' => ['Petugas yang dipilih sedang nonaktif atau tidak valid.'],
            ]);
        }

        // 3. Validasi bentrok (Kecualikan jadwal ini sendiri)
        $bentrok = $this->detectCollision(
            $validated['officer_id'],
            $jadwal->titik_air_id,
            $tanggalMulai,
            $durasiMenit,
            $jadwal->jadwal_id
        );

        if ($bentrok) {
            $pesanBentrok = $bentrok['alasan'];
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $pesanBentrok,
                    'conflict' => $bentrok['conflict_schedule'],
                ], 409);
            }

            return redirect()->back()->withInput()->withErrors([
                'bentrok' => $pesanBentrok,
            ]);
        }

        $jadwal->update([
            'petugas_id' => $validated['officer_id'],
            'tanggal_jadwal' => $tanggalMulai,
            'durasi_menit' => $durasiMenit,
            'catatan' => $validated['catatan'] ?? $jadwal->catatan,
        ]);

        $adminUser = Auth::user();
        AuditLog::createAuditLog(
            $adminUser ? $adminUser->id : 1,
            'Mengubah Jadwal Pemeriksaan',
            'jadwal_pemeriksaan',
            $jadwal->jadwal_id,
            "Jadwal #{$jadwal->jadwal_id} diubah/dipindahkan ke {$tanggalMulai->format('d M Y H:i')} dengan durasi {$durasiMenit} menit."
        );

        $msg = "Jadwal pemeriksaan berhasil diperbarui.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'data' => $jadwal->fresh(['titikAir', 'petugas']),
            ]);
        }

        return redirect()->route('inspections.index')->with('success', $msg);
    }

    /**
     * PBI-09: Ubah status jadwal pemeriksaan (PATCH /inspections/:id/status)
     * Aktor: Admin & Petugas yang ditugaskan (UC-14)
     */
    public function updateStatus(Request $request, $id)
    {
        $jadwal = JadwalPemeriksaan::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:dijadwalkan,selesai,dibatalkan',
        ]);

        $user = Auth::user() ?? User::where('role', 'admin')->first();

        // Jika petugas yang login, pastikan ini jadwal miliknya (UC-14)
        if ($user && $user->role === 'petugas' && $jadwal->petugas_id !== $user->id) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki wewenang untuk mengubah status jadwal milik petugas lain.',
                ], 403);
            }
            return redirect()->back()->withErrors(['status' => 'Anda hanya dapat mengubah jadwal milik Anda sendiri.']);
        }

        $jadwal->status_pemeriksaan = $validated['status'];
        $jadwal->save();

        AuditLog::createAuditLog(
            $user ? $user->id : 1,
            'Pembaruan Status Jadwal Pemeriksaan',
            'jadwal_pemeriksaan',
            $jadwal->jadwal_id,
            "Status jadwal pemeriksaan #{$jadwal->jadwal_id} diubah menjadi '{$validated['status']}' oleh {$user->name}."
        );

        $msg = "Status jadwal pemeriksaan berhasil diubah menjadi '" . ucfirst($validated['status']) . "'.";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'data' => $jadwal->fresh(['titikAir', 'petugas']),
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Algoritma Deteksi Bentrok Jadwal (Conflict Collision Detection)
     * Memeriksa irisan waktu aktif terhadap petugas atau titik air yang sama.
     */
    private function detectCollision($officerId, $waterPointId, Carbon $newStart, int $durationMinutes, $excludeId = null): ?array
    {
        $newEnd = $newStart->copy()->addMinutes($durationMinutes);

        // Ambil semua jadwal yang masih berstatus 'dijadwalkan' untuk petugas atau titik air yang sama
        $candidates = JadwalPemeriksaan::with(['titikAir', 'petugas'])
            ->where('status_pemeriksaan', 'dijadwalkan')
            ->where(function ($q) use ($officerId, $waterPointId) {
                $q->where('petugas_id', $officerId)
                  ->orWhere('titik_air_id', $waterPointId);
            })
            ->when($excludeId, function ($q) use ($excludeId) {
                $q->where('jadwal_id', '!=', $excludeId);
            })
            ->get();

        foreach ($candidates as $existing) {
            $existingStart = $existing->tanggal_jadwal;
            $existingEnd = $existing->waktu_selesai;

            // Kondisi irisan waktu interval: (StartA < EndB) && (EndA > StartB)
            if ($newStart->lt($existingEnd) && $newEnd->gt($existingStart)) {
                $alasan = '';
                if ($existing->petugas_id == $officerId && $existing->titik_air_id == $waterPointId) {
                    $alasan = "Jadwal bentrok: Petugas {$existing->petugas->name} dan Titik Air {$existing->titikAir->nama} sudah memiliki jadwal aktif pada jam tersebut ({$existingStart->format('H:i')} - {$existingEnd->format('H:i')} WIB).";
                } elseif ($existing->petugas_id == $officerId) {
                    $alasan = "Jadwal bentrok: Petugas {$existing->petugas->name} sudah memiliki jadwal lain di {$existing->titikAir->nama} pada jam {$existingStart->format('H:i')} - {$existingEnd->format('H:i')} WIB.";
                } else {
                    $alasan = "Jadwal bentrok: Titik Air {$existing->titikAir->nama} sudah dijadwalkan diperiksa oleh {$existing->petugas->name} pada jam {$existingStart->format('H:i')} - {$existingEnd->format('H:i')} WIB.";
                }

                return [
                    'alasan' => $alasan,
                    'conflict_schedule' => [
                        'id' => $existing->jadwal_id,
                        'petugas' => $existing->petugas->name,
                        'titik_air' => $existing->titikAir->nama,
                        'mulai' => $existingStart->toIso8601String(),
                        'selesai' => $existingEnd->toIso8601String(),
                        'durasi' => $existing->durasi_menit,
                    ],
                ];
            }
        }

        return null;
    }
}
