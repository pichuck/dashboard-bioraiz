<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// PENTING: Route spesifik HARUS di atas route resource
// Routes untuk latest data (HARUS SEBELUM apiResource)
Route::get('/Suhu/latest', [App\Http\Controllers\Api\TransaksiSensorSuhuController::class, 'latest']);
Route::get('/Tanah/latest', [App\Http\Controllers\Api\TransaksiSensorTanahController::class, 'latest']);

// Routes untuk date range filtering
Route::post('/Suhu/date-range', [App\Http\Controllers\Api\TransaksiSensorSuhuController::class, 'getByDateRange']);
Route::post('/Tanah/date-range', [App\Http\Controllers\Api\TransaksiSensorTanahController::class, 'getByDateRange']);

// Route untuk soil humidity level filtering
Route::get('/Tanah/humidity/{level}', [App\Http\Controllers\Api\TransaksiSensorTanahController::class, 'getByHumidityLevel']);

// API Resource routes (HARUS SETELAH route spesifik)
Route::apiResource('/Suhu', App\Http\Controllers\Api\TransaksiSensorSuhuController::class);
Route::apiResource('/Tanah', App\Http\Controllers\Api\TransaksiSensorTanahController::class);