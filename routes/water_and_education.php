<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaterPointController;
use App\Http\Controllers\AnnouncementController;

/*
|--------------------------------------------------------------------------
| AquaCare - Rute PBI Fadhlan:
| - PBI-01: Manajemen Data Titik Sumber Air
| - PBI-10: Peta Sebaran Titik Air
| - PBI-12: Pengumuman dan Edukasi Air Bersih
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ENDPOINT REST API (Sesuai Tabel 15 Proposal)
// ==========================================
Route::prefix('api')->group(function () {

    // PBI-01: Titik Sumber Air
    Route::get('/water-points', [WaterPointController::class, 'index']);
    Route::post('/water-points', [WaterPointController::class, 'store']);
    Route::get('/water-points/{id}', [WaterPointController::class, 'show']);
    Route::put('/water-points/{id}', [WaterPointController::class, 'update']);
    Route::delete('/water-points/{id}', [WaterPointController::class, 'destroy']);

    // PBI-10: Data Titik untuk Marker Peta Berwarna
    Route::get('/map/water-points', [WaterPointController::class, 'map']);

    // PBI-12: Pengumuman & Edukasi
    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::get('/announcements/{id}', [AnnouncementController::class, 'show']);
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy']);
});

// ==========================================
// 2. WEB UI ROUTES (Blade Views Interaktif)
// ==========================================
Route::middleware(['web'])->group(function () {

    // PBI-10: Peta Sebaran Interaktif (Warga, Petugas, Admin)
    Route::get('/peta', [WaterPointController::class, 'map'])->name('water-points.map');

    // PBI-12: Portal Edukasi Air Bersih untuk Warga
    Route::get('/edukasi', [AnnouncementController::class, 'portalWarga'])->name('announcements.warga');
    Route::get('/edukasi/{id}', [AnnouncementController::class, 'show'])->name('announcements.warga.show');

    // Admin Panel: Titik Sumber Air (PBI-01)
    Route::prefix('admin')->group(function () {
        Route::resource('water-points', WaterPointController::class);
        Route::resource('announcements', AnnouncementController::class);
    });
});

