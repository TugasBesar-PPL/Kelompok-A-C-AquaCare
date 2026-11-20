<?php

namespace App\Http\Controllers;

use App\Models\LaporanMasalahAir;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ReportController extends Controller
{
    public function create(): View
    {
        return view('reports.create');
    }

    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        $validated = $request->validate([
            'status' => ['nullable', 'string', 'max:50'],
        ]);
        $selectedStatus = $validated['status'] ?? null;

        $statuses = LaporanMasalahAir::query()
            ->where('user_id', $userId)
            ->distinct()
            ->orderBy('status_laporan')
            ->pluck('status_laporan');

        $reports = LaporanMasalahAir::query()
            ->where('user_id', $userId)
            ->when($selectedStatus !== null && $selectedStatus !== '', function ($query) use ($selectedStatus) {
                $query->where('status_laporan', $selectedStatus);
            })
            ->orderByDesc('tanggal_laporan')
            ->orderByDesc('laporan_id')
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', [
            'reports' => $reports,
            'statuses' => $statuses,
            'selectedStatus' => $selectedStatus,
            'hasReports' => $statuses->isNotEmpty(),
        ]);
    }

    public function show(Request $request, int $id): View
    {
        $report = $this->reportForOwner($request, $id);
        $historyAvailable = Schema::hasTable('riwayat_penanganan');
        $history = collect();

        if ($historyAvailable) {
            $history = DB::table('riwayat_penanganan')
                ->select([
                    'penanganan_id',
                    'laporan_id',
                    'tanggal_pembaruan',
                    'status_penanganan',
                    'catatan',
                    'foto_bukti',
                ])
                ->where('laporan_id', $report->laporan_id)
                ->orderByDesc('tanggal_pembaruan')
                ->orderByDesc('penanganan_id')
                ->get();
        }

        return view('reports.show', [
            'report' => $report,
            'history' => $history,
            'historyAvailable' => $historyAvailable,
        ]);
    }

    public function reportPhoto(Request $request, int $id): StreamedResponse
    {
        $report = $this->reportForOwner($request, $id);

        return $this->privatePhotoResponse($report->foto_laporan);
    }

    public function historyPhoto(Request $request, int $id, int $penangananId): StreamedResponse
    {
        $report = $this->reportForOwner($request, $id);

        abort_unless(Schema::hasTable('riwayat_penanganan'), 404);

        $photoPath = DB::table('riwayat_penanganan')
            ->where('penanganan_id', $penangananId)
            ->where('laporan_id', $report->laporan_id)
            ->value('foto_bukti');

        return $this->privatePhotoResponse($photoPath);
    }

    private function reportForOwner(Request $request, int $id): LaporanMasalahAir
    {
        return LaporanMasalahAir::query()
            ->where('user_id', $request->user()->id)
            ->whereKey($id)
            ->firstOrFail();
    }

    private function privatePhotoResponse(?string $path): StreamedResponse
    {
        abort_if(
            ! is_string($path)
                || $path === ''
                || str_contains($path, '..')
                || str_starts_with($path, '/')
                || str_contains($path, '\\'),
            404,
        );

        $disk = Storage::disk('local');
        abort_unless($disk->exists($path), 404);

        $mimeType = $disk->mimeType($path);
        abort_unless(in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], true), 404);

        return $disk->response($path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ], 'inline');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:100'],
            'wilayah' => ['required', 'string', 'max:150'],
            'lokasi.latitude' => ['required', 'numeric', 'between:-90,90'],
            'lokasi.longitude' => ['required', 'numeric', 'between:-180,180'],
            'lokasi.alamat' => ['required', 'string', 'max:1000'],
            'deskripsi' => ['required', 'string', 'max:10000'],
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'water_point_id' => ['nullable', 'integer', 'min:1'],
        ]);

        try {
            $photoPath = $request->file('foto')->store('laporan', 'local');
        } catch (Throwable $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'foto' => 'Foto gagal disimpan. Silakan coba unggah kembali.',
            ]);
        }

        if (! is_string($photoPath) || $photoPath === '') {
            throw ValidationException::withMessages([
                'foto' => 'Foto gagal disimpan. Silakan coba unggah kembali.',
            ]);
        }

        try {
            LaporanMasalahAir::create([
                'user_id' => $request->user()->id,
                'titik_air_id' => $validated['water_point_id'] ?? null,
                'kategori_masalah' => $validated['kategori'],
                'wilayah' => $validated['wilayah'],
                'deskripsi' => $validated['deskripsi'],
                'lokasi' => $validated['lokasi'],
                'foto_laporan' => $photoPath,
                'tanggal_laporan' => now(),
                'status_laporan' => 'Menunggu',
            ]);
        } catch (Throwable $exception) {
            try {
                if (! Storage::disk('local')->delete($photoPath)) {
                    logger()->warning('Failed to remove uploaded report photo after report creation failed.', [
                        'photo_path' => $photoPath,
                    ]);
                }
            } catch (Throwable $cleanupException) {
                try {
                    logger()->error('Error while removing uploaded report photo after report creation failed.', [
                        'photo_path' => $photoPath,
                        'cleanup_exception' => $cleanupException->getMessage(),
                    ]);
                } catch (Throwable) {
                    // Preserve the original report creation exception if logging is also unavailable.
                }
            }

            throw $exception;
        }

        return redirect()
            ->route('reports.create')
            ->with('success', 'Laporan berhasil dikirim. Status laporan: Menunggu.');
    }
}
