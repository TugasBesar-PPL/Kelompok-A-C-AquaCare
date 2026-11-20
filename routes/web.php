<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes — Memerlukan login + role admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard placeholder (akan diimplementasi PBI-02)
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // PBI-17: Log Aktivitas
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});

/*
|--------------------------------------------------------------------------
| API Routes — JSON responses
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('api')->group(function () {
    // PBI-17: API endpoint log aktivitas
    Route::get('/audit-logs', [AuditLogController::class, 'apiIndex'])->name('api.audit-logs.index');
});
