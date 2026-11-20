<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitoringKualitasAirController;

Route::get('/monitoring', [MonitoringKualitasAirController::class, 'index'])->name('monitoring.index');
Route::get('/monitoring/input', [MonitoringKualitasAirController::class, 'create'])->name('monitoring.create');
Route::post('/monitoring/input', [MonitoringKualitasAirController::class, 'store'])->name('monitoring.store');
Route::get('/monitoring/titik/{id}', [MonitoringKualitasAirController::class, 'show'])->name('monitoring.show');

Route::get('/water-points/{id}/history', [MonitoringKualitasAirController::class, 'historyApi'])->name('water-points.history');

Route::get('/', function () {
    return view('welcome');
});
