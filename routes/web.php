<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/quick-login/{role?}', [AuthController::class, 'quickLogin'])->name('quick-login');

// Dashboard Routes (Mockup Gambar 8)
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');
Route::get('/dashboard/latest-measurements', [DashboardController::class, 'latestMeasurements'])->name('dashboard.latest-measurements');

// PBI-07: Verifikasi dan Penugasan Laporan
Route::get('/reports/verification', [VerificationController::class, 'index'])->name('reports.verification');
Route::patch('/reports/{id}/verification', [VerificationController::class, 'update'])->name('reports.verification.update');
Route::get('/reports/{id}', [VerificationController::class, 'show'])->name('reports.show');
