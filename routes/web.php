<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BakuMutuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - AquaCare (Platform Monitoring & Pelaporan Air Bersih)
|--------------------------------------------------------------------------
*/

// Home / Root Redirect
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('baku-mutu.admin.index');
        } elseif ($user->isPetugas()) {
            return redirect()->route('baku-mutu.index');
        } else {
            return redirect()->route('warga.lapor');
        }
    }
    return redirect()->route('login');
})->name('home');

// ── 1. Authentication Routes (FD-01 & FD-02) ──────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/quick-login/{role?}', [AuthController::class, 'quickLogin'])->name('quick-login');

// ── 2. Layanan Warga (Portal Pelaporan Air) ────────────────────────────────
Route::middleware(['auth', 'role:warga,admin'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/lapor', [WargaController::class, 'createReport'])->name('lapor');
    Route::post('/lapor', [WargaController::class, 'storeReport'])->name('lapor.store');
    Route::get('/laporan', [WargaController::class, 'myReports'])->name('laporan');
});

// ── 3. Panel Administrator (FD-03 & PBI-18) ──────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // PBI-18: Baku Mutu Parameter Air (Web Admin)
    Route::prefix('water-standards')->name('baku-mutu.admin.')->group(function () {
        Route::get('/', [BakuMutuController::class, 'manage'])->name('index');
        Route::get('/{kode}/edit', [BakuMutuController::class, 'editPage'])->name('edit');
        Route::put('/{kode}', [BakuMutuController::class, 'updateWeb'])->name('update');
        Route::get('/{kode}/riwayat', [BakuMutuController::class, 'historyPage'])->name('history');
    });

    // FD-03: Kelola Akun Petugas & Peran
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{id}/role', [UserController::class, 'updateRole'])->name('update-role');
    });
});

// ── 4. JSON API Baku Mutu (PBI-18) ───────────────────────────────────────
Route::prefix('water-standards')->name('baku-mutu.')->group(function () {
    Route::get('/', [BakuMutuController::class, 'index'])->name('index');
    Route::put('/{kode}', [BakuMutuController::class, 'update'])->name('update');
    Route::get('/{kode}/history', [BakuMutuController::class, 'history'])->name('history');
});
