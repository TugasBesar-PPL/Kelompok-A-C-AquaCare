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
        Schema::create('pengukuran_kualitas_air', function (Blueprint $table) {
            $table->id('pengukuran_id');
            $table->unsignedBigInteger('titik_air_id');
            $table->foreign('titik_air_id')->references('titik_air_id')->on('titik_sumber_air')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('jadwal_id')->nullable();
            $table->timestamp('tanggal_pengukuran')->useCurrent();
            $table->decimal('ph', 4, 2);
            $table->decimal('kekeruhan', 6, 2)->nullable();
            $table->decimal('tds', 7, 2)->nullable();
            $table->decimal('suhu', 5, 2)->nullable();
            $table->decimal('sisa_klor', 5, 2)->nullable();
            $table->enum('status_kelayakan', ['Layak', 'Waspada', 'Tidak Layak'])->default('Layak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengukuran_kualitas_air');
    }
};

