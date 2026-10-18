<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MetricaController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (sin token)
Route::post('/login', [AuthController::class, 'login']);
Route::get('/eventos', [EventoController::class, 'index']);

// Rutas protegidas: exigen un token Bearer válido de Sanctum (RNF 1.3)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/perfil', [AuthController::class, 'perfil']);
    Route::put('/eventos/{evento}', [EventoController::class, 'update']);

    // Gate 'ver-metricas' aplicado con el middleware 'can' (RNF 2.2)
    Route::get('/admin/metricas', MetricaController::class)
        ->middleware('can:ver-metricas');
});