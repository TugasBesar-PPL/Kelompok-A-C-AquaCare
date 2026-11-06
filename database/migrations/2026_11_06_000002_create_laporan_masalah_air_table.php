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
        Schema::create('laporan_masalah_air', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->string('kode_laporan')->unique(); // LP-0128, LP-0136, etc.
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('titik_air_id')->nullable();
            $table->foreign('titik_air_id')->references('titik_air_id')->on('titik_sumber_air')->nullOnDelete();
            $table->string('kategori_masalah'); // Air keruh, Air berbau, Aliran mati, Pipa bocor, Lainnya
            $table->string('wilayah'); // RW 01, RW 02, etc.
            $table->string('lokasi'); // Alamat spesifik
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('deskripsi');
            $table->string('foto_laporan')->nullable();
            $table->timestamp('tanggal_laporan')->useCurrent();
            $table->enum('status_laporan', ['Menunggu', 'Ditugaskan', 'Diproses', 'Selesai', 'Ditolak'])->default('Menunggu');
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->nullable();
            $table->foreignId('admin_verifikator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamp('tanggal_ditugaskan')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_masalah_air');
    }
};

