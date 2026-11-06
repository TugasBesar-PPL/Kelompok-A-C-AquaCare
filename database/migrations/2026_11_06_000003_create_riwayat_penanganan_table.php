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
        Schema::create('riwayat_penanganan', function (Blueprint $table) {
            $table->id('penanganan_id');
            $table->unsignedBigInteger('laporan_id');
            $table->foreign('laporan_id')->references('laporan_id')->on('laporan_masalah_air')->onDelete('cascade');
            $table->foreignId('petugas_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('tanggal_pembaruan')->useCurrent();
            $table->string('status_penanganan'); // Diproses, Selesai
            $table->text('catatan');
            $table->string('foto_bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_penanganan');
    }
};

