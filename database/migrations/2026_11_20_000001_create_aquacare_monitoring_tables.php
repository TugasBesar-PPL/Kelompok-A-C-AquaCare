<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('titik_sumber_air')) {
            Schema::create('titik_sumber_air', function (Blueprint $table) {
                $table->id('titik_air_id');
                $table->string('kode_titik')->unique(); // contoh: ST-03
                $table->string('nama');
                $table->string('jenis');
                $table->string('alamat');
                $table->string('wilayah');
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->string('foto')->nullable();
                $table->string('status_terkini')->default('Layak'); // Layak, Waspada, Tidak Layak
                $table->timestamp('pengukuran_terakhir')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('baku_mutu_parameter')) {
            Schema::create('baku_mutu_parameter', function (Blueprint $table) {
                $table->id('parameter_id');
                $table->string('kode_parameter'); // ph, kekeruhan, tds, suhu, sisa_klor
                $table->string('nama_parameter');
                $table->string('satuan');
                $table->decimal('nilai_minimum', 8, 2)->nullable();
                $table->decimal('nilai_maksimum', 8, 2)->nullable();
                $table->decimal('batas_waspada_min', 8, 2)->nullable();
                $table->decimal('batas_waspada_maks', 8, 2)->nullable();
                $table->timestamp('tanggal_berlaku')->useCurrent();
                $table->unsignedBigInteger('diubah_oleh')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pengukuran_kualitas_air')) {
            Schema::create('pengukuran_kualitas_air', function (Blueprint $table) {
                $table->id('pengukuran_id');
                $table->unsignedBigInteger('titik_air_id');
                $table->unsignedBigInteger('petugas_id')->nullable();
                $table->unsignedBigInteger('jadwal_id')->nullable();
                $table->timestamp('tanggal_pengukuran');
                $table->decimal('ph', 5, 2);
                $table->decimal('kekeruhan', 8, 2);
                $table->decimal('tds', 8, 2);
                $table->decimal('suhu', 5, 2);
                $table->decimal('sisa_klor', 5, 2);
                $table->string('status_kelayakan')->default('Layak'); // Layak, Waspada, Tidak Layak
                $table->timestamps();

                $table->foreign('titik_air_id')
                    ->references('titik_air_id')
                    ->on('titik_sumber_air')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengukuran_kualitas_air');
        Schema::dropIfExists('baku_mutu_parameter');
        Schema::dropIfExists('titik_sumber_air');
    }
};