<?php

namespace App\Http\Controllers;

use App\Services\ElectionService;
use App\Services\InstitutionService;
use App\Services\VoteTallyService;

class ReportController extends Controller
{
    private InstitutionService $institutionService;
    private ElectionService $electionService;
    private VoteTallyService $voteTallyService;

    public function __construct(InstitutionService $institutionService, ElectionService $electionService, VoteTallyService $voteTallyService)
    {
    }

    public function index()
    {
    }

    public function padron()
    {
    }

    public function padronFirmas()
    {
    }

    public function padronVotos()
    {
    }

    public function conteoCero()
    {
    }

    public function actaApertura()
    {
    }

    public function actaCierre()
    {
    }

    public function actaResultados()
    {
    }

    public function incidentes()
    {
    }

    public function carteles($id)
    {
    }

    public function instrucciones()
    {
    }

    public function resultados()
    {
    }

    public function consultaPopularResumen()
    {
    }

    public function consultaPopularPorMesa()
    {
    }

    public function consultaPopularPorSeccion()
    {
    }
}
