<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('titik_sumber_air', function (Blueprint $table) {
            $table->id('titik_air_id');
            $table->string('kode_titik')->unique(); // e.g. ST-01, ST-03
            $table->string('nama'); // e.g. Sumur Bor RW 03
            $table->string('jenis'); // Sumur Bor, Mata Air, PDAM, Sumur Gali
            $table->string('alamat');
            $table->string('wilayah'); // RW 01, RW 02, RW 03, etc.
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('foto')->nullable();
            $table->enum('status_terkini', ['Layak', 'Waspada', 'Tidak Layak'])->default('Layak');
            $table->timestamp('pengukuran_terakhir')->nullable();
            $table->timestamps();
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

