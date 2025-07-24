<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Controller::class, 'index']);
Route::post('/location', [\App\Http\Controllers\Controller::class, 'geo_info_store'])->name('location.store');
