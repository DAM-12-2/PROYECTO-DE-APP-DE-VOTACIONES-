<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Party;
use App\Models\TeeMember;
use App\Models\Vote;
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
    $inst = $this->institutionService->getSettings();
    $institucion = $inst->nombre;
    $tally = $this->voteTallyService->tallyVotes(Vote::all());
    $parties = Party::all();
    $mesas = Mesa::all();
    $mesaSeleccionada = null;
    $results = $tally->partyVotes;
    $isConsultaPopular = $this->electionService->isConsultaPopular();
    $votosValidos = $tally->votosValidos();

    return view('admin.reportes.acta_cierre', compact(
        'institucion', 'tally', 'parties', 'mesas', 'mesaSeleccionada',
        'results', 'isConsultaPopular', 'votosValidos'
    ));
}

public function actaResultados()
{
    $institucion = $this->institutionService->getSettings()->nombre;
    $logoPath = $this->institutionService->getSettings()->logoPath;
    $tally = $this->voteTallyService->tallyVotes(Vote::all());
    $parties = Party::all();
    $results = $tally->partyVotes;
    $isConsultaPopular = $this->electionService->isConsultaPopular();
    $votosValidos = $tally->votosValidos();
    $ganadorData = $this->voteTallyService->verificarGanador();
    $empate = $ganadorData === null && $votosValidos > 0;
    $maxVotos = $ganadorData['votos'] ?? 0;
    $porcentajeGanador = $ganadorData['porcentaje'] ?? 0;
    $ganador = $ganadorData ? (object) $ganadorData : null;
    $teeMembers = TeeMember::with('student')->get();
    $tee = $teeMembers->first();

    return view('admin.reportes.acta_oficial_resultados', compact(
        'institucion', 'logoPath', 'tally', 'parties', 'results',
        'isConsultaPopular', 'votosValidos', 'ganador', 'empate',
        'maxVotos', 'porcentajeGanador', 'teeMembers', 'tee'
    ));
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
    $ganador = $this->voteTallyService->verificarGanador();
    return view('admin.resultados', compact('ganador'));
}

public function consultaPopularResumen()
{
    $institucion = $this->institutionService->getSettings()->nombre;
    $logoPath = $this->institutionService->getSettings()->logoPath;
    $tally = $this->voteTallyService->tallyVotes(Vote::all());
    $votosValidos = $tally->votosValidos();
    $participacion = $tally->totalVotes > 0 ? round(($votosValidos / $tally->totalVotes) * 100, 1) : 0;

    if ($this->electionService->isConsultaPopular() && $votosValidos > 0) {
        $ganador = $tally->siCount > $tally->noCount ? 'SI'
            : ($tally->noCount > $tally->siCount ? 'NO' : 'EMPATE');
    } else {
        $ganador = null;
    }

    return view('admin.reportes.consulta_popular_resumen', compact(
        'institucion', 'logoPath', 'tally', 'votosValidos', 'participacion', 'ganador'
    ));
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
