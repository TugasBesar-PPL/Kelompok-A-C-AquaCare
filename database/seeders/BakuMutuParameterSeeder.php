<?php

namespace Database\Seeders;

use App\Models\BakuMutuParameter;
use Illuminate\Database\Seeder;

class BakuMutuParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nilai berikut adalah contoh awal, bukan acuan regulasi resmi.
        $parameters = [
            [
                'nama_parameter' => 'pH',
                'satuan' => 'pH',
                'nilai_minimum' => 6.50,
                'nilai_maksimum' => 8.50,
            ],
            [
                'nama_parameter' => 'Suhu Air',
                'satuan' => '°C',
                'nilai_minimum' => 25.00,
                'nilai_maksimum' => 32.00,
            ],
            [
                'nama_parameter' => 'Oksigen Terlarut (DO)',
                'satuan' => 'mg/L',
                'nilai_minimum' => 5.00,
                'nilai_maksimum' => null,
            ],
            [
                'nama_parameter' => 'Kekeruhan',
                'satuan' => 'NTU',
                'nilai_minimum' => null,
                'nilai_maksimum' => 25.00,
            ],
            [
                'nama_parameter' => 'Amonia (NH3)',
                'satuan' => 'mg/L',
                'nilai_minimum' => null,
                'nilai_maksimum' => 0.02,
            ],
        ];

        foreach ($parameters as $parameter) {
            BakuMutuParameter::updateOrCreate(
                ['nama_parameter' => $parameter['nama_parameter']],
                $parameter,
            );
        }
    }
}
