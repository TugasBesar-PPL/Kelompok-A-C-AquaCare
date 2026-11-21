<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\VerificationController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/quick-login/{role?}', [AuthController::class, 'quickLogin'])->name('quick-login');

// Google OAuth Routes
Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
})->name('google.redirect');

Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName(),
            'password' => bcrypt(str()->random(24)),
            'role' => 'warga',
            'is_active' => true,
        ]);

        Auth::login($user, true);

        return redirect()->route('dashboard')->with('success', "Selamat datang, {$user->name}!");
    } catch (\Throwable $e) {
        return redirect()->route('login')->withErrors([
            'email' => 'Gagal masuk menggunakan akun Google: ' . $e->getMessage(),
        ]);
    }
})->name('google.callback');

// Dashboard Routes (Mockup Gambar 8)
Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');
Route::get('/dashboard/latest-measurements', [DashboardController::class, 'latestMeasurements'])->name('dashboard.latest-measurements');

// PBI-07: Verifikasi dan Penugasan Laporan (Fathir)
Route::get('/reports/verification', [VerificationController::class, 'index'])->name('reports.verification');
Route::patch('/reports/{id}/verification', [VerificationController::class, 'update'])->name('reports.verification.update');
Route::get('/reports/{id}', [VerificationController::class, 'show'])->name('reports.show');

// PBI-09: Penjadwalan Pemeriksaan Rutin (Fathir)
Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections.index');
Route::post('/inspections', [InspectionController::class, 'store'])->name('inspections.store');
Route::patch('/inspections/{id}', [InspectionController::class, 'update'])->name('inspections.update');
Route::patch('/inspections/{id}/status', [InspectionController::class, 'updateStatus'])->name('inspections.status');
