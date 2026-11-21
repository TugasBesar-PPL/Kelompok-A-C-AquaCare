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
        Schema::create('jadwal_pemeriksaan', function (Blueprint $table) {
            $table->id('jadwal_id');
            $table->unsignedBigInteger('titik_air_id');
            $table->foreign('titik_air_id')->references('titik_air_id')->on('titik_sumber_air')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('tanggal_jadwal'); // tanggal dan jam mulai
            $table->integer('durasi_menit')->default(60); // durasi dalam menit
            $table->enum('status_pemeriksaan', ['dijadwalkan', 'selesai', 'dibatalkan'])->default('dijadwalkan');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pemeriksaan');
    }
};
