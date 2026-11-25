<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('warga');
            $table->boolean('is_active')->default(true);
            $table->integer('login_gagal')->default(0);
            $table->timestamp('terkunci_sampai')->nullable();
            $table->string('phone')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'is_active',
                'login_gagal',
                'terkunci_sampai',
                'phone',
            ]);
        });
    }
};