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
        $this->institutionService = $institutionService;
        $this->electionService = $electionService;
        $this->voteTallyService = $voteTallyService;
    }

    public function index()
    {
        return view('admin.reportes.index');
    }

    public function padron()
    {
        return view('admin.reportes.padron');
    }

    public function padronFirmas()
    {
        return view('admin.reportes.padron_firmas');
    }

    public function padronVotos()
    {
        return view('admin.reportes.padron_votos');
    }

    public function conteoCero()
    {
        return view('admin.reportes.conteo_cero');
    }

    public function actaApertura()
    {
        return view('admin.reportes.acta_apertura');
    }

    public function actaCierre()
    {
    }

    public function actaResultados()
    {
    }

    public function incidentes()
    {
        return view('admin.reportes.incidentes');
    }

    public function carteles($id)
    {
        return view('admin.reportes.carteles');
    }

    public function instrucciones()
    {
        return view('admin.reportes.instrucciones');
    }

    public function resultados()
    {
    }

    public function consultaPopularResumen()
    {
    }

    public function consultaPopularPorMesa()
    {
        return view('admin.reportes.consulta_popular_por_mesa');
    }

    public function consultaPopularPorSeccion()
    {
        return view('admin.reportes.consulta_popular_por_seccion');
    }
}
