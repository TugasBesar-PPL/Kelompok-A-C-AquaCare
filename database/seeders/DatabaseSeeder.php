<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database (Sprint 1: PBI-01 & PBI-12).
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Fadhlan Admin',
            'email' => 'admin@aquacare.com',
        ]);

        $this->call([
            TitikSumberAirSeeder::class,
            PengumumanSeeder::class,
        ]);
    }
}
