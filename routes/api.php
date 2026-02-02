<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EquipController;
use App\Http\Controllers\Api\EstadiController;
use App\Http\Controllers\Api\JugadorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Public
Route::apiResource('jugadors', JugadorController::class)
    ->only(['index', 'show'])
    ->parameters(['jugadors' => 'jugadora'])
    ->names('api.public.jugadors');

Route::apiResource('equips', EquipController::class)
    ->only(['index', 'show'])
    ->names('api.public.equips');

Route::apiResource('estadis', EstadiController::class)
    ->only(['index', 'show'])
    ->names('api.public.estadis');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Protected
    Route::apiResource('jugadors', JugadorController::class)
        ->except(['index', 'show'])
        ->parameters(['jugadors' => 'jugadora'])
        ->names('api.jugadors');

    Route::apiResource('equips', EquipController::class)
        ->except(['index', 'show'])
        ->names('api.equips');

    Route::apiResource('estadis', EstadiController::class)
        ->except(['index', 'show'])
        ->names('api.estadis');
});

