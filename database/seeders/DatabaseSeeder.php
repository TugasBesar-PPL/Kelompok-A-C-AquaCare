<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat akun Admin default
        $admin = User::factory()->create([
            'name' => 'Admin AquaCare',
            'email' => 'admin@aquacare.id',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Buat akun Petugas default
        $petugas = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'petugas@aquacare.id',
            'role' => 'petugas',
            'is_active' => true,
        ]);

        // Buat akun Warga default
        $warga = User::factory()->create([
            'name' => 'Siti Rahma',
            'email' => 'warga@aquacare.id',
            'role' => 'warga',
            'is_active' => true,
        ]);

        // Buat sample audit logs untuk testing
        $sampleLogs = [
            [
                'user_id' => $admin->id,
                'aksi' => 'create',
                'entitas' => 'users',
                'entitas_id' => $petugas->id,
                'waktu' => now()->subDays(5)->setTime(9, 15),
            ],
            [
                'user_id' => $admin->id,
                'aksi' => 'create',
                'entitas' => 'users',
                'entitas_id' => $warga->id,
                'waktu' => now()->subDays(5)->setTime(9, 20),
            ],
            [
                'user_id' => $admin->id,
                'aksi' => 'update',
                'entitas' => 'water_standards',
                'entitas_id' => 1,
                'waktu' => now()->subDays(4)->setTime(10, 30),
            ],
            [
                'user_id' => $admin->id,
                'aksi' => 'update',
                'entitas' => 'water_standards',
                'entitas_id' => 2,
                'waktu' => now()->subDays(4)->setTime(10, 45),
            ],
            [
                'user_id' => $admin->id,
                'aksi' => 'verify',
                'entitas' => 'reports',
                'entitas_id' => 1,
                'waktu' => now()->subDays(3)->setTime(14, 0),
            ],
            [
                'user_id' => $admin->id,
                'aksi' => 'assign',
                'entitas' => 'reports',
                'entitas_id' => 1,
                'waktu' => now()->subDays(3)->setTime(14, 5),
            ],
            [
                'user_id' => $petugas->id,
                'aksi' => 'status_change',
                'entitas' => 'reports',
                'entitas_id' => 1,
                'waktu' => now()->subDays(2)->setTime(11, 20),
            ],
            [
                'user_id' => $admin->id,
                'aksi' => 'reject',
                'entitas' => 'reports',
                'entitas_id' => 2,
                'waktu' => now()->subDays(2)->setTime(15, 30),
            ],
            [
                'user_id' => $admin->id,
                'aksi' => 'update',
                'entitas' => 'users',
                'entitas_id' => $petugas->id,
                'waktu' => now()->subDays(1)->setTime(8, 45),
            ],
            [
                'user_id' => $admin->id,
                'aksi' => 'create',
                'entitas' => 'users',
                'entitas_id' => 4,
                'waktu' => now()->subDays(1)->setTime(13, 10),
            ],
            [
                'user_id' => $admin->id,
                'aksi' => 'update',
                'entitas' => 'water_standards',
                'entitas_id' => 3,
                'waktu' => now()->setTime(10, 15),
            ],
            [
                'user_id' => $petugas->id,
                'aksi' => 'status_change',
                'entitas' => 'reports',
                'entitas_id' => 1,
                'waktu' => now()->setTime(9, 30),
            ],
        ];

        foreach ($sampleLogs as $log) {
            AuditLog::create($log);
        }
    }
}
