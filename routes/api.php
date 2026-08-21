<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::middleware('auth:sanctum')->group(function () {
    //
});

// 📌 Rutas del Módulo de Estudiantes (CSV y Búsqueda)
Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);
Route::put('/students/{id}', [StudentController::class, 'update']);
Route::delete('/students/{id}', [StudentController::class, 'destroy']);
Route::post('/students/import', [StudentController::class, 'import']);
Route::get('/students/export', [StudentController::class, 'export']);
Route::get('/students/search', [StudentController::class, 'search']);