<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('baku_mutu_parameter', function (Blueprint $table) {
            $table->string('nama_parameter')->unique();
            $table->string('satuan', 30);
            $table->decimal('nilai_minimum', 10, 2)->nullable();
            $table->decimal('nilai_maksimum', 10, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('baku_mutu_parameter', function (Blueprint $table) {
            $table->dropUnique(['nama_parameter']);
            $table->dropColumn([
                'nama_parameter',
                'satuan',
                'nilai_minimum',
                'nilai_maksimum',
            ]);
        });
    }
};
