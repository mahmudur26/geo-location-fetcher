<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::get('/', [Controller::class, 'index']);
Route::post('/location', [Controller::class, 'geo_info_store'])->name('location.store');
