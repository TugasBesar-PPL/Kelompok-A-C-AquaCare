<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_masalah_air', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->foreignId('user_id')->constrained('users');
            // API name is water_point_id; ERD name is titik_air_id. No FK until
            // the target table and key have been confirmed.
            $table->unsignedBigInteger('titik_air_id')->nullable();
            $table->string('kategori_masalah', 100);
            $table->string('wilayah', 150);
            $table->text('deskripsi');
            $table->json('lokasi');
            $table->string('foto_laporan');
            $table->timestamp('tanggal_laporan');
            $table->string('status_laporan', 50)->default('Menunggu');
            $table->string('prioritas', 50)->nullable();
            $table->foreignId('admin_verifikator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamp('tanggal_ditugaskan')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'tanggal_laporan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_masalah_air');
    }
};
