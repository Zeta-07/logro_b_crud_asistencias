<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/asistencias', [AsistenciaController::class, 'getAllAsistencias']);
Route::get('/asistencias/{id}', [AsistenciaController::class, 'getAsistenciaById']);
Route::post('/asistencias', [AsistenciaController::class, 'createAsistencia']);
Route::put('/asistencias/{id}', [AsistenciaController::class, 'updateAsistencia']);
Route::delete('/asistencias/{id}', [AsistenciaController::class, 'deleteAsistencia']);


