<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('baku_mutu_parameter', function (Blueprint $table) {
            $table->string('kode_parameter')->nullable();
            $table->decimal('batas_waspada_min', 10, 2)->nullable();
            $table->decimal('batas_waspada_maks', 10, 2)->nullable();
            $table->date('tanggal_berlaku')->nullable();
            $table->foreignId('diubah_oleh')->nullable()->constrained('users')->nullOnDelete();
        });

        $examples = [
            'pH' => [
                'kode_parameter' => 'ph',
                'nama_parameter' => 'pH',
                'satuan' => 'pH',
                'nilai_minimum' => 6.50,
                'nilai_maksimum' => 8.50,
                'batas_waspada_min' => 6.00,
                'batas_waspada_maks' => 9.00,
            ],
            'Kekeruhan' => [
                'kode_parameter' => 'kekeruhan',
                'nama_parameter' => 'Kekeruhan',
                'satuan' => 'NTU',
                'nilai_minimum' => 0.00,
                'nilai_maksimum' => 5.00,
                'batas_waspada_min' => 0.00,
                'batas_waspada_maks' => 10.00,
            ],
            'Oksigen Terlarut (DO)' => [
                'kode_parameter' => 'tds',
                'nama_parameter' => 'TDS',
                'satuan' => 'mg/L',
                'nilai_minimum' => 0.00,
                'nilai_maksimum' => 500.00,
                'batas_waspada_min' => 0.00,
                'batas_waspada_maks' => 1000.00,
            ],
            'Suhu Air' => [
                'kode_parameter' => 'suhu',
                'nama_parameter' => 'Suhu',
                'satuan' => '°C',
                'nilai_minimum' => 20.00,
                'nilai_maksimum' => 30.00,
                'batas_waspada_min' => 15.00,
                'batas_waspada_maks' => 35.00,
            ],
            'Amonia (NH3)' => [
                'kode_parameter' => 'sisa_klor',
                'nama_parameter' => 'Sisa Klor',
                'satuan' => 'mg/L',
                'nilai_minimum' => 0.20,
                'nilai_maksimum' => 0.50,
                'batas_waspada_min' => 0.10,
                'batas_waspada_maks' => 1.00,
            ],
        ];

        foreach ($examples as $legacyName => $parameter) {
            DB::table('baku_mutu_parameter')
                ->where('nama_parameter', $legacyName)
                ->whereNull('kode_parameter')
                ->update([
                    ...$parameter,
                    'tanggal_berlaku' => '2026-11-14',
                ]);
        }

        Schema::table('baku_mutu_parameter', function (Blueprint $table) {
            $table->dropUnique(['nama_parameter']);
            $table->unique(['kode_parameter', 'tanggal_berlaku']);
        });
    }

    public function down(): void
    {
        $hasVersionedNames = DB::table('baku_mutu_parameter')
            ->select('nama_parameter')
            ->groupBy('nama_parameter')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasVersionedNames) {
            throw new RuntimeException('Cannot roll back while parameter history contains multiple versions.');
        }

        Schema::table('baku_mutu_parameter', function (Blueprint $table) {
            $table->dropUnique(['kode_parameter', 'tanggal_berlaku']);
            $table->dropForeign(['diubah_oleh']);
            $table->dropColumn([
                'kode_parameter',
                'batas_waspada_min',
                'batas_waspada_maks',
                'tanggal_berlaku',
                'diubah_oleh',
            ]);
            $table->unique('nama_parameter');
        });
    }
};
