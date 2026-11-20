<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
