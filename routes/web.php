<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/water-points');
});

require base_path('routes/water_and_education.php');
