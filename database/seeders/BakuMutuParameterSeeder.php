<?php

namespace Database\Seeders;

use App\Models\BakuMutuParameter;
use Illuminate\Database\Seeder;

class BakuMutuParameterSeeder extends Seeder
{
    /**
     * Seed data contoh baku mutu air.
     * Nilai ini perlu disesuaikan dengan regulasi resmi yang berlaku.
     */
    public function run(): void
    {
        $tanggalBerlaku = '2026-11-14';

        $parameters = [
            [
                'kode_parameter'     => 'ph',
                'nama_parameter'     => 'pH',
                'satuan'             => 'pH',
                'nilai_minimum'      => 6.50,
                'nilai_maksimum'     => 8.50,
                'batas_waspada_min'  => 6.00,
                'batas_waspada_maks' => 9.00,
            ],
            [
                'kode_parameter'     => 'kekeruhan',
                'nama_parameter'     => 'Kekeruhan',
                'satuan'             => 'NTU',
                'nilai_minimum'      => 0.00,
                'nilai_maksimum'     => 5.00,
                'batas_waspada_min'  => 0.00,
                'batas_waspada_maks' => 10.00,
            ],
            [
                'kode_parameter'     => 'tds',
                'nama_parameter'     => 'TDS',
                'satuan'             => 'mg/L',
                'nilai_minimum'      => 0.00,
                'nilai_maksimum'     => 500.00,
                'batas_waspada_min'  => 0.00,
                'batas_waspada_maks' => 1000.00,
            ],
            [
                'kode_parameter'     => 'suhu',
                'nama_parameter'     => 'Suhu',
                'satuan'             => '°C',
                'nilai_minimum'      => 20.00,
                'nilai_maksimum'     => 30.00,
                'batas_waspada_min'  => 15.00,
                'batas_waspada_maks' => 35.00,
            ],
            [
                'kode_parameter'     => 'sisa_klor',
                'nama_parameter'     => 'Sisa Klor',
                'satuan'             => 'mg/L',
                'nilai_minimum'      => 0.20,
                'nilai_maksimum'     => 0.50,
                'batas_waspada_min'  => 0.10,
                'batas_waspada_maks' => 1.00,
            ],
        ];

        foreach ($parameters as $parameter) {
            BakuMutuParameter::updateOrCreate(
                [
                    'kode_parameter'  => $parameter['kode_parameter'],
                    'tanggal_berlaku' => $tanggalBerlaku,
                ],
                [...$parameter, 'tanggal_berlaku' => $tanggalBerlaku],
            );
        }
    }
}
