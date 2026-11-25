<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BakuMutuController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - AquaCare (Platform Monitoring & Pelaporan Air Bersih)
| PIC: Arrival Sudrajat (Fitur Dasar: FD-01, FD-02, FD-03 & PBI-18)
|--------------------------------------------------------------------------
*/

// Home / Root Redirect
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('baku-mutu.admin.index');
        }

        return redirect()->route('baku-mutu.index');
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

// ── 2. Panel Administrator (FD-03 & PBI-18) ──────────────────────────────
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

// ── 3. JSON API Baku Mutu (PBI-18) ───────────────────────────────────────
Route::prefix('water-standards')->name('baku-mutu.')->group(function () {
    Route::get('/', [BakuMutuController::class, 'index'])->name('index');
    Route::put('/{kode}', [BakuMutuController::class, 'update'])->name('update');
    Route::get('/{kode}/history', [BakuMutuController::class, 'history'])->name('history');
});

// ── 4. Laporan Warga (PBI-06) ────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/my-reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/my-reports/{id}/photo', [ReportController::class, 'reportPhoto'])
        ->whereNumber('id')
        ->name('reports.photo');

    Route::get('/my-reports/{id}/history/{penangananId}/photo', [ReportController::class, 'historyPhoto'])
        ->whereNumber('id')
        ->whereNumber('penangananId')
        ->name('reports.history-photo');

    Route::get('/my-reports/{id}', [ReportController::class, 'show'])
        ->whereNumber('id')
        ->name('reports.show');

    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
});