<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PBI-12: Pengumuman dan Edukasi Air Bersih
     */
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id('pengumuman_id');
            $table->unsignedBigInteger('admin_id');
            $table->string('judul', 255);
            $table->text('isi');
            $table->enum('kategori', ['pengumuman', 'edukasi'])->default('pengumuman');
            $table->string('gambar', 255)->nullable();
            $table->timestamp('tanggal_publikasi')->useCurrent();
            $table->enum('status_publikasi', ['draft', 'published'])->default('published');
            $table->timestamps();

            // Indeks untuk filter kategori & status
            $table->index('kategori');
            $table->index('status_publikasi');
            $table->index('tanggal_publikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};

