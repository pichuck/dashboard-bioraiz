<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/Suhu', App\Http\Controllers\Api\TransaksiSensorSuhuController::class);

Route::apiResource('/Tanah', App\Http\Controllers\Api\TransaksiSensorTanahController::class);