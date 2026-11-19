<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GeoController;

Route::get('/regions', [GeoController::class, 'regions']);
Route::get('/regions/all', [GeoController::class, 'regionsAll']);
Route::get('/provinces/all', [GeoController::class, 'provincesAll']);
Route::get('/cities/all', [GeoController::class, 'citiesAll']);
Route::get('/barangays/all', [GeoController::class, 'barangaysAll']);
Route::get('/provinces', [GeoController::class, 'provinces']);
Route::get('/cities', [GeoController::class, 'cities']);
Route::get('/barangays', [GeoController::class, 'barangays']);


