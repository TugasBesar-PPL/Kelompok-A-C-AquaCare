<?php

use App\Http\Controllers\BakuMutuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// API routes (JSON)
Route::prefix('water-standards')->name('baku-mutu.')->group(function () {
    Route::get('/', [BakuMutuController::class, 'index'])->name('index');
    Route::put('/{kode}', [BakuMutuController::class, 'update'])->name('update');
    Route::get('/{kode}/history', [BakuMutuController::class, 'history'])->name('history');
});

// Admin Web UI routes
Route::prefix('admin/water-standards')->name('baku-mutu.admin.')->group(function () {
    Route::get('/', [BakuMutuController::class, 'manage'])->name('index');
    Route::get('/{kode}/edit', [BakuMutuController::class, 'editPage'])->name('edit');
    Route::put('/{kode}', [BakuMutuController::class, 'updateWeb'])->name('update');
    Route::get('/{kode}/riwayat', [BakuMutuController::class, 'historyPage'])->name('history');
});
