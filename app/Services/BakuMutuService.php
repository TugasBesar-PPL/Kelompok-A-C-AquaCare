<?php

namespace App\Services;

use App\Models\BakuMutuParameter;
use Illuminate\Support\Collection;

class BakuMutuService
{
    /**
     * Kembalikan satu versi yang berlaku hari ini untuk setiap parameter,
     * diurutkan berdasarkan kode_parameter.
     */
    public function versiBerlaku(): Collection
    {
        return BakuMutuParameter::whereDate('tanggal_berlaku', '<=', now()->toDateString())
            ->orderBy('kode_parameter')
            ->orderByDesc('tanggal_berlaku')
            ->get()
            ->unique('kode_parameter')
            ->values();
    }

    /**
     * Simpan perubahan batas baku mutu sebagai versi baru.
     * Versi lama tetap tersimpan (append-only / immutable history).
     */
    public function simpanVersiBaru(string $kode, array $data, ?int $userId): BakuMutuParameter
    {
        $terakhir = BakuMutuParameter::where('kode_parameter', $kode)
            ->orderByDesc('tanggal_berlaku')
            ->firstOrFail();

        return BakuMutuParameter::create([
            'kode_parameter'     => $kode,
            'nama_parameter'     => $terakhir->nama_parameter,
            'satuan'             => $terakhir->satuan,
            'nilai_minimum'      => $data['nilai_minimum'],
            'nilai_maksimum'     => $data['nilai_maksimum'],
            'batas_waspada_min'  => $data['batas_waspada_min'],
            'batas_waspada_maks' => $data['batas_waspada_maks'],
            'tanggal_berlaku'    => $data['tanggal_berlaku'],
            'diubah_oleh'        => $userId,
        ]);
    }

    /**
     * Kembalikan seluruh riwayat versi untuk satu kode parameter,
     * versi terbaru lebih dahulu. Abort 404 jika kode tidak dikenal.
     */
    public function riwayat(string $kode): Collection
    {
        $history = BakuMutuParameter::where('kode_parameter', $kode)
            ->orderByDesc('tanggal_berlaku')
            ->orderByDesc('id')
            ->get();

        if ($history->isEmpty()) {
            abort(404, "Kode parameter '{$kode}' tidak ditemukan.");
        }

        return $history;
    }
}
