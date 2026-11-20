<?php

namespace Database\Seeders;

use App\Models\PengukuranKualitasAir;
use App\Models\TitikSumberAir;
use Illuminate\Database\Seeder;

class MonitoringDemoSeeder extends Seeder
{
    public function run(): void
    {
        $titik = TitikSumberAir::updateOrCreate(
            ['kode_titik' => 'ST-03'],
            [
                'nama'                => 'Sumur Bor RW 03',
                'jenis'               => 'Sumur Bor',
                'alamat'              => 'Jl. Sukamaju No. 10',
                'wilayah'             => 'Kel. Sukamaju',
                'status_terkini'      => 'Waspada',
                'pengukuran_terakhir' => '2026-10-24 08:30:00',
            ]
        );

        $samples = [
            ['2026-10-04 08:30:00', 7.2, 1.6, 220, 26.1, 0.4, 'Layak'],
            ['2026-10-08 08:30:00', 7.1, 1.9, 225, 26.2, 0.4, 'Layak'],
            ['2026-10-12 08:30:00', 7.0, 2.1, 230, 26.4, 0.3, 'Layak'],
            ['2026-10-16 08:30:00', 7.1, 2.6, 235, 26.3, 0.4, 'Layak'],
            ['2026-10-20 08:30:00', 7.2, 2.9, 240, 26.5, 0.4, 'Layak'],
            ['2026-10-22 08:30:00', 7.0, 3.4, 242, 26.4, 0.4, 'Waspada'],
            ['2026-10-24 08:30:00', 7.1, 3.8, 245, 26.5, 0.4, 'Waspada'],
        ];

        foreach ($samples as [$tgl, $ph, $keruh, $tds, $suhu, $klor, $status]) {
            PengukuranKualitasAir::create([
                'titik_air_id'       => $titik->titik_air_id,
                'tanggal_pengukuran' => $tgl,
                'ph'                 => $ph,
                'kekeruhan'          => $keruh,
                'tds'                => $tds,
                'suhu'               => $suhu,
                'sisa_klor'          => $klor,
                'status_kelayakan'   => $status,
            ]);
        }
    }
}