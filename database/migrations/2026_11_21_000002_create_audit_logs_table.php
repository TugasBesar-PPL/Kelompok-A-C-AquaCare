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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('aksi');
            $table->string('entitas');
            $table->unsignedBigInteger('entitas_id');
            $table->timestamp('waktu')->useCurrent();

            // Indexes untuk performa query filter
            $table->index('aksi');
            $table->index('entitas');
            $table->index('waktu');
            $table->index(['user_id', 'waktu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
