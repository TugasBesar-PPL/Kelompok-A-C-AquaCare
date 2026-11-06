<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-01: Manajemen Data Titik Sumber Air
     * PBI-10: Peta Sebaran Titik Air
     */
    public function up(): void
    {
        Schema::create('titik_sumber_air', function (Blueprint $table) {
            $table->id('titik_air_id');
            $table->string('nama', 255);
            $table->string('jenis', 255); // Sumur Bor, Sumur Gali, Mata Air, PDAM, dll
            $table->string('alamat', 255);
            $table->string('wilayah', 255); // RW 01, RW 02, Sukamaju, dll
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('foto', 255)->nullable();
            $table->string('status_terkini', 50)->default('Belum Diuji'); // Layak, Waspada, Tidak Layak, Belum Diuji
            $table->timestamp('pengukuran_terakhir')->nullable();
            $table->timestamps();

            // Indeks untuk pencarian dan filter cepat (PBI-01 & PBI-10)
            $table->index('wilayah');
            $table->index('status_terkini');
            $table->index('jenis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titik_sumber_air');
    }
};

