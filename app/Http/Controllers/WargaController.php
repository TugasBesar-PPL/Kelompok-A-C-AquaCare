<?php

namespace App\Http\Controllers;

use App\Models\LaporanMasalahAir;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WargaController extends Controller
{
    public function createReport()
    {
        return view('warga.create-report');
    }

    public function storeReport(Request $request)
    {
        $validated = $request->validate([
            'kategori_masalah' => ['required', 'string', 'max:100'],
            'wilayah' => ['required', 'string', 'max:100'],
            'alamat' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'deskripsi' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // maks 5MB sesuai NFR-02
        ], [
            'kategori_masalah.required' => 'Kategori masalah wajib diisi.',
            'wilayah.required' => 'Wilayah wajib diisi.',
            'alamat.required' => 'Alamat kejadian wajib diisi.',
            'deskripsi.required' => 'Deskripsi masalah wajib diisi.',
            'foto.max' => 'Ukuran foto maksimal 5 MB.',
            'foto.mimes' => 'Format foto harus berupa JPG atau PNG.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('laporan', 'public');
        }

        // Generate kode laporan unik: LP-XXXX
        $count = LaporanMasalahAir::count() + 1;
        $kodeLaporan = 'LP-' . str_pad((string)$count, 4, '0', STR_PAD_LEFT);

        LaporanMasalahAir::create([
            'kode_laporan' => $kodeLaporan,
            'user_id' => Auth::id(),
            'kategori_masalah' => $validated['kategori_masalah'],
            'wilayah' => $validated['wilayah'],
            'lokasi' => $validated['alamat'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'deskripsi' => $validated['deskripsi'],
            'foto_laporan' => $fotoPath,
            'status_laporan' => 'Menunggu',
            'tanggal_laporan' => Carbon::now(),
        ]);

        return redirect()->route('warga.laporan')
            ->with('success', "Laporan dengan kode {$kodeLaporan} berhasil dikirim! Status awal: Menunggu.");
    }

    public function myReports()
    {
        $reports = LaporanMasalahAir::where('user_id', Auth::id())
            ->orderByDesc('tanggal_laporan')
            ->paginate(10);

        return view('warga.my-reports', compact('reports'));
    }
}
