<?php

namespace App\Http\Controllers;

use App\Models\LaporanMasalahAir;
use App\Models\PengukuranKualitasAir;
use App\Models\TitikSumberAir;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilan utama Dashboard Admin sesuai Mockup Gambar 8
     */
    public function index()
    {
        // 1. Data Ringkasan Titik Sumber Air
        $totalLayak = TitikSumberAir::where('status_terkini', 'Layak')->count();
        $totalWaspada = TitikSumberAir::where('status_terkini', 'Waspada')->count();
        $totalTidakLayak = TitikSumberAir::where('status_terkini', 'Tidak Layak')->count();
        $totalTitik = TitikSumberAir::count();

        // 2. Data Ringkasan Laporan
        $laporanMasuk = LaporanMasalahAir::where('status_laporan', 'Menunggu')->count();
        $laporanDiproses = LaporanMasalahAir::whereIn('status_laporan', ['Ditugaskan', 'Diproses'])->count();
        $laporanSelesai = LaporanMasalahAir::where('status_laporan', 'Selesai')->count();

        // 3. Tabel Laporan Warga Terbaru (Sesuai Mockup Gambar 8)
        $laporanTerbaru = LaporanMasalahAir::with(['pelapor', 'titikAir', 'petugas'])
            ->orderBy('tanggal_laporan', 'desc')
            ->take(10)
            ->get();

        // 4. Pengukuran Terbaru (Sesuai Mockup Gambar 8)
        $pengukuranTerbaru = PengukuranKualitasAir::with('titikAir')
            ->orderBy('tanggal_pengukuran', 'desc')
            ->take(5)
            ->get();

        // 5. Daftar Petugas Aktif untuk Modal Verifikasi Cepat
        $petugasAktif = User::where('role', 'petugas')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.index', compact(
            'totalLayak',
            'totalWaspada',
            'totalTidakLayak',
            'totalTitik',
            'laporanMasuk',
            'laporanDiproses',
            'laporanSelesai',
            'laporanTerbaru',
            'pengukuranTerbaru',
            'petugasAktif'
        ));
    }

    /**
     * Endpoint API: GET /dashboard/summary (PBI-02)
     */
    public function summary()
    {
        return response()->json([
            'total_layak' => TitikSumberAir::where('status_terkini', 'Layak')->count(),
            'total_waspada' => TitikSumberAir::where('status_terkini', 'Waspada')->count(),
            'total_tidak_layak' => TitikSumberAir::where('status_terkini', 'Tidak Layak')->count(),
            'laporan_masuk' => LaporanMasalahAir::where('status_laporan', 'Menunggu')->count(),
            'laporan_diproses' => LaporanMasalahAir::whereIn('status_laporan', ['Ditugaskan', 'Diproses'])->count(),
            'laporan_selesai' => LaporanMasalahAir::where('status_laporan', 'Selesai')->count(),
            'notifikasi_belum_dibaca' => 4,
        ]);
    }

    /**
     * Endpoint API: GET /dashboard/latest-measurements (PBI-02)
     */
    public function latestMeasurements(Request $request)
    {
        $limit = $request->query('limit', 10);
        $data = PengukuranKualitasAir::with('titikAir')
            ->orderBy('tanggal_pengukuran', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'water_point_id' => $item->titik_air_id,
                    'nama_titik' => $item->titikAir->nama ?? '-',
                    'tanggal_ukur' => $item->tanggal_pengukuran->toIso8601String(),
                    'ph' => $item->ph,
                    'status' => $item->status_kelayakan,
                ];
            });

        return response()->json([
            'data' => $data,
        ]);
    }
}

