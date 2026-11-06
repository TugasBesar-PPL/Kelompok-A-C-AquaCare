<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TitikSumberAir;

class TitikSumberAirSeeder extends Seeder
{
    public function run(): void
    {
        $points = [
            [
                'nama' => 'Sumur Bor RW 03',
                'jenis' => 'Sumur Bor',
                'alamat' => 'Jl. Melati No. 12, RW 03',
                'wilayah' => 'RW 03',
                'latitude' => -6.9745210,
                'longitude' => 107.6301240,
                'foto' => null,
                'status_terkini' => 'Waspada',
                'pengukuran_terakhir' => '2026-10-24 08:30:00',
            ],
            [
                'nama' => 'Mata Air Kenanga',
                'jenis' => 'Mata Air',
                'alamat' => 'Jl. Kenanga No. 5, RW 01',
                'wilayah' => 'RW 01',
                'latitude' => -6.9712500,
                'longitude' => 107.6324800,
                'foto' => null,
                'status_terkini' => 'Layak',
                'pengukuran_terakhir' => '2026-10-24 09:15:00',
            ],
            [
                'nama' => 'Sumur Gali RW 05',
                'jenis' => 'Sumur Gali',
                'alamat' => 'Gg. Anggrek No. 8, RW 05',
                'wilayah' => 'RW 05',
                'latitude' => -6.9789100,
                'longitude' => 107.6285400,
                'foto' => null,
                'status_terkini' => 'Tidak Layak',
                'pengukuran_terakhir' => '2026-10-24 10:00:00',
            ],
            [
                'nama' => 'PDAM Blok A',
                'jenis' => 'PDAM',
                'alamat' => 'Komplek Permata Blok A4',
                'wilayah' => 'RW 02',
                'latitude' => -6.9698400,
                'longitude' => 107.6351200,
                'foto' => null,
                'status_terkini' => 'Layak',
                'pengukuran_terakhir' => '2026-10-24 10:45:00',
            ],
            [
                'nama' => 'Sumur Bor RW 06',
                'jenis' => 'Sumur Bor',
                'alamat' => 'Jl. Flamboyan No. 17, RW 06',
                'wilayah' => 'RW 06',
                'latitude' => -6.9763200,
                'longitude' => 107.6342100,
                'foto' => null,
                'status_terkini' => 'Layak',
                'pengukuran_terakhir' => '2026-10-24 11:30:00',
            ],
        ];

        foreach ($points as $item) {
            TitikSumberAir::updateOrCreate(['nama' => $item['nama']], $item);
        }
    }
}

