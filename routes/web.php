<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\GoogleDistanceFetchController;
use App\Http\Controllers\QRCodeGeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [Controller::class, 'index']);
Route::post('/location', [Controller::class, 'geo_info_store'])->name('location.store');


Route::get('/qr/{code}', [QRCodeGeoController::class, 'preview'])->name('qr.preview');
Route::post('/qr/{code}', [QRCodeGeoController::class, 'action'])->name('qr.action');

Route::get('/google-distance', [GoogleDistanceFetchController::class, 'distance_fetch']);
