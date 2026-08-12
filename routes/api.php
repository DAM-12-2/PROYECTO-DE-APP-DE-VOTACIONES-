<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrnaController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\VoteController;

Route::middleware('auth:sanctum')->get(function (Request $request) {
    return $request->user();
});

Route::post('/urnas/activar', [UrnaController::class, 'activar'])->name('urnas.activar');
Route::post('/urnas/desactivar', [UrnaController::class, 'desactivar'])->name('urnas.desactivar');

Route::get('/ganador', [ResultController::class, 'apiVerificarGanador'])->name('resultados.ganador');

Route::get('/partidos', [PartyController::class, 'index'])->name('partidos.index');
Route::get('/candidatos', [CandidatoController::class, 'index'])->name('candidatos.index');

route::post('/votar', [VoteController::class, 'store'])->name('votos.store');