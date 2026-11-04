<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrnaController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\StudentController;

Route::post('/urnas/activar', [UrnaController::class, 'activar'])->name('urnas.activar');
Route::post('/urnas/desactivar', [UrnaController::class, 'desactivar'])->name('urnas.desactivar');

Route::get('/ganador', [ResultController::class, 'apiVerificarGanador'])->name('resultados.ganador');

Route::get('/partidos', [PartyController::class, 'index'])->name('partidos.index');
Route::get('/candidatos', [CandidatoController::class, 'index'])->name('candidatos.index');

Route::post('/votar', [VoteController::class, 'store'])->name('votos.store');

Route::middleware(['web', 'auth', 'role:admin,tee'])->prefix('resultados')->group(function () {
    Route::get('/', [ResultController::class, 'apiResultados']);
    Route::get('/por-mesa', [ResultController::class, 'apiResultadosPorMesa']);
    Route::get('/por-seccion', [ResultController::class, 'apiResultadosPorSeccion']);
    Route::get('/resumen', [ResultController::class, 'apiResumen']);
    Route::get('/ganador', [ResultController::class, 'apiVerificarGanador']);
    Route::get('/exportar', [ResultController::class, 'exportarResultadosCsv']);
});

// Rutas del Modulo de Estudiantes (CSV y Busqueda)
Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);
Route::put('/students/{id}', [StudentController::class, 'update']);
Route::delete('/students/{id}', [StudentController::class, 'destroy']);
Route::post('/students/import', [StudentController::class, 'import']);
Route::get('/students/export', [StudentController::class, 'export']);
Route::get('/students/search', [StudentController::class, 'search']);
