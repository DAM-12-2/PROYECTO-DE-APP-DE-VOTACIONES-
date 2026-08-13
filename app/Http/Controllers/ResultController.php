<?php

namespace App\Http\Controllers;

use App\Services\ElectionService;
use App\Services\InstitutionService;
use App\Services\VoteTallyService;
use App\Services\BitacoraService;
use Illuminate\Support\Facades\Log;

class ResultController extends Controller
{
    private VoteTallyService $voteTallyService;
    private ElectionService $electionService;
    private InstitutionService $institutionService;
    private BitacoraService $bitacoraService;

    public function __construct(VoteTallyService $voteTallyService, ElectionService $electionService, InstitutionService $institutionService, BitacoraService $bitacoraService)
    {
        $this->voteTallyService = $voteTallyService;
        $this->electionService = $electionService;
        $this->institutionService = $institutionService;
        $this->bitacoraService = $bitacoraService;
    }

    public function resultados()
    {
        $datos = $this->electionService->resultadosCompletos();
        return response()->json(['success' => true, ...$datos]);
    }

    public function verificarGanadorAutomatico()
    {
        return response()->json(['success' => true, ...$this->electionService->verificarGanador()]);
    }

    public function apiVerificarGanador()
    {
        return response()->json(['success' => true, ...$this->electionService->verificarGanador()]);
    }

    public function exportarResultadosCsv()
    {
        $datos = $this->electionService->resultadosCompletos();

        return response()->streamDownload(function () use ($datos) {
            $salida = fopen('php://output', 'w');
            fputcsv($salida, ['Partido', 'Votos', 'Porcentaje']);
            foreach ($datos['partidos'] as $partido) {
                fputcsv($salida, [$partido['nombre'], $partido['votos'], $partido['porcentaje'] . '%']);
            }
            fclose($salida);
        }, 'resultados.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="resultados.csv"',
        ]);
    }
}
