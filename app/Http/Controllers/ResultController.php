<?php

namespace App\Http\Controllers;

use App\Services\BitacoraService;
use App\Services\ElectionService;
use App\Services\InstitutionService;
use App\Services\VoteTallyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

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

    private function checkElectionClosed(): ?JsonResponse
    {
        if ($this->electionService->isElectionOpen()) {
            return response()->json([
                'error' => 'Resultados no disponibles mientras la elección está abierta. Cierre las votaciones primero.'
            ], 403);
        }
        return null;
    }

    // ===== API ENDPOINTS =====

    public function apiResultados()
    {
        $blocked = $this->checkElectionClosed();
        if ($blocked) return $blocked;

        $result = $this->voteTallyService->tallyVotes();
        $parties = $this->voteTallyService->formatPartyResults($result);
        $ganador = $this->calculateGanador($result);

        return response()->json([
            'total_votos' => $result->totalVotes,
            'blancos' => $result->blancos,
            'nulos' => $result->nulos,
            'votos_validos' => $result->votosValidos(),
            'partidos' => $parties,
            'ganador' => $ganador,
            'es_consulta_popular' => $this->electionService->isConsultaPopular(),
        ]);
    }

    public function apiResultadosPorMesa()
    {
        $blocked = $this->checkElectionClosed();
        if ($blocked) return $blocked;

        $results = $this->voteTallyService->tallyVotesByMesa();

        return response()->json($results);
    }

    public function apiResultadosPorSeccion()
    {
        $blocked = $this->checkElectionClosed();
        if ($blocked) return $blocked;

        $results = $this->voteTallyService->tallyVotesBySeccion();

        return response()->json($results);
    }

    public function apiResumen()
    {
        $blocked = $this->checkElectionClosed();
        if ($blocked) return $blocked;

        $result = $this->voteTallyService->tallyVotes();
        $totalEstudiantes = \App\Models\Student::where('estado', 1)->count();

        return response()->json([
            'total_votos' => $result->totalVotes,
            'votos_validos' => $result->votosValidos(),
            'blancos' => $result->blancos,
            'nulos' => $result->nulos,
            'total_electores' => $totalEstudiantes,
            'porcentaje_participacion' => $totalEstudiantes > 0 ? round(($result->totalVotes / $totalEstudiantes) * 100, 1) : 0,
            'es_consulta_popular' => $this->electionService->isConsultaPopular(),
        ]);
    }

    public function apiVerificarGanador()
    {
        $blocked = $this->checkElectionClosed();
        if ($blocked) return $blocked;

        $result = $this->voteTallyService->tallyVotes();
        $ganador = $this->calculateGanador($result);

        return response()->json([
            'hay_ganador' => $ganador !== null,
            'ganador' => $ganador,
            'es_consulta_popular' => $this->electionService->isConsultaPopular(),
        ]);
    }

    public function exportarResultadosCsv()
    {
        $blocked = $this->checkElectionClosed();
        if ($blocked) return $blocked;

        $result = $this->voteTallyService->tallyVotes();
        $parties = $this->voteTallyService->formatPartyResults($result);

        $filename = 'resultados_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($parties, $result) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            if ($this->electionService->isConsultaPopular()) {
                fputcsv($file, ['Opción', 'Votos', 'Porcentaje']);
                foreach ($parties as $party) {
                    fputcsv($file, [$party['nombre'], $party['votos'], $party['porcentaje'] . '%']);
                }
                fputcsv($file, []);
                fputcsv($file, ['Total Votos', $result->totalVotes]);
                fputcsv($file, ['Blancos', $result->blancos]);
                fputcsv($file, ['Nulos', $result->nulos]);
                fputcsv($file, ['Votos Válidos', $result->votosValidos()]);
            } else {
                fputcsv($file, ['Partido', 'Siglas', 'Votos', 'Porcentaje']);
                foreach ($parties as $party) {
                    fputcsv($file, [$party['nombre'], $party['siglas'], $party['votos'], $party['porcentaje'] . '%']);
                }
                fputcsv($file, []);
                fputcsv($file, ['Total Votos', $result->totalVotes]);
                fputcsv($file, ['Blancos', $result->blancos]);
                fputcsv($file, ['Nulos', $result->nulos]);
                fputcsv($file, ['Votos Válidos', $result->votosValidos()]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ===== WEB ENDPOINTS (original) =====

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

    public function verificarGanadorAutomatico()
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

    // ===== HELPER =====

    private function calculateGanador($result): ?array
    {
        if ($this->electionService->isConsultaPopular()) {
            $validos = $result->votosValidos();
            if ($validos === 0) return null;
            
            if ($result->siCount > $result->noCount) {
                return [
                    'opcion' => 'SÍ',
                    'votos' => $result->siCount,
                    'porcentaje' => round(($result->siCount / $validos) * 100, 1),
                ];
            } elseif ($result->noCount > $result->siCount) {
                return [
                    'opcion' => 'NO',
                    'votos' => $result->noCount,
                    'porcentaje' => round(($result->noCount / $validos) * 100, 1),
                ];
            }
            return ['opcion' => 'EMPATE', 'votos' => $result->siCount, 'porcentaje' => 50.0];
        }

        $parties = \App\Models\Party::where('estado', 1)->get()->all();
        $sorted = $result->sortedPartyResults($parties);
        
        if (empty($sorted)) return null;
        
        $top = $sorted[0];
        $validos = $result->votosValidos();
        
        if ($validos > 0 && $top['votos'] > ($validos / 2)) {
            return [
                'siglas' => $top['siglas'],
                'nombre' => $top['nombre'],
                'votos' => $top['votos'],
                'porcentaje' => $top['porcentaje'],
                'mayoria_absoluta' => true,
            ];
        }

        return [
            'siglas' => $top['siglas'],
            'nombre' => $top['nombre'],
            'votos' => $top['votos'],
            'porcentaje' => $top['porcentaje'],
            'mayoria_absoluta' => false,
        ];
    }
}