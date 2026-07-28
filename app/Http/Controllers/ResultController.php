<?php

namespace App\Http\Controllers;

use App\Services\ElectionService;
use App\Services\InstitutionService;
use App\Services\VoteTallyService;
use App\Services\BitacoraService;

class ResultController extends Controller
{
    private VoteTallyService $voteTallyService;
    private ElectionService $electionService;
    private InstitutionService $institutionService;
    private BitacoraService $bitacoraService;

    public function __construct(VoteTallyService $voteTallyService, ElectionService $electionService, InstitutionService $institutionService, BitacoraService $bitacoraService)
    {
    }

    public function resultados()
    {
    }

    public function verificarGanadorAutomatico()
    {
    }

    public function apiVerificarGanador()
    {
    }

    public function exportarResultadosCsv()
    {
    }
}
