<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Baku Mutu Parameter Air (PBI-18)
        $this->call(BakuMutuParameterSeeder::class);

        // 2. Akun Pengguna Dasar (FD-01, FD-02, FD-03)
        // Admin (Arrival Sudrajat - PIC PBI-18 & Fitur Dasar)
        User::firstOrCreate(
            ['email' => 'admin@aquacare.id'],
            [
                'name' => 'Arrival Sudrajat (Admin)',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'phone' => '081234567890',
            ]
        );

        // Petugas Lapangan
        User::firstOrCreate(
            ['email' => 'budi@aquacare.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
                'is_active' => true,
                'phone' => '082211334455',
            ]
        );

        // Warga 1 (Siti Rahma - sesuai mockup portal warga)
        User::firstOrCreate(
            ['email' => 'siti@gmail.com'],
            [
                'name' => 'Siti Rahma',
                'password' => Hash::make('password123'),
                'role' => 'warga',
                'is_active' => true,
                'phone' => '081399887766',
            ]
        );

        // Warga 2 (Joko Susilo)
        User::firstOrCreate(
            ['email' => 'joko@gmail.com'],
            [
                'name' => 'Joko Susilo',
                'password' => Hash::make('password123'),
                'role' => 'warga',
                'is_active' => true,
                'phone' => '081288776655',
            ]
        );
    }
}
