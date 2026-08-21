<?php

namespace App\Http\Controllers;

use App\Services\BitacoraService;
use App\Services\ElectionService;
use App\Services\InstitutionService;
use App\Services\VoteTallyService;
use Illuminate\Http\Request;
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
        try {
            $ganador = $this->voteTallyService->verificarGanador();
            return view('admin.resultados', compact('ganador'));
        } catch (\Exception $e) {
            Log::error('Error al cargar los resultados: ' . $e->getMessage());
            return back()->withErrors('Error al cargar los resultados.');
        }
    }

    public function apiVerificarGanador()
    {
        try {
            $resultados = $this->voteTallyService->obtenerResultados();
            return response()->json([
                'success' => true,
                'ganador' => $this->voteTallyService->verificarGanador(),
                'partidos' => $resultados['partidos'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error al verificar ganador: ' . $e->getMessage());
            return response()->json(['success' => false, 'ganador' => null]);
        }
    }

    public function verificarGanadorAutomatico()
    {
        return $this->apiVerificarGanador();
    }

    public function exportarResultadosCsv()
    {
        try {
            $datos = $this->voteTallyService->obtenerResultados();

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
        } catch (\Exception $e) {
            Log::error('Error al exportar los resultados: ' . $e->getMessage());
            return back()->withErrors('Error al exportar los resultados.');
        }
    }
}
