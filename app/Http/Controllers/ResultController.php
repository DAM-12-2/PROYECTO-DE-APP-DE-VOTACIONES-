<?php

namespace App\Http\Controllers;

use App\Services\ElectionService;
use App\Services\InstitutionService;
use App\Services\VoteTallyService;
use App\Services\BitacoraService;
<<<<<<< HEAD
use Illuminate\Support\Facades\Log;
=======
use Illuminate\Http\Request;
use Exception;
>>>>>>> 28a1fc9 (feat: integración de servicios esenciales y corrección de constructores)

class ResultController extends Controller
{
    private VoteTallyService $voteTallyService;
    private ElectionService $electionService;
    private InstitutionService $institutionService;
    private BitacoraService $bitacoraService;

<<<<<<< HEAD
    public function __construct(VoteTallyService $voteTallyService, ElectionService $electionService, InstitutionService $institutionService, BitacoraService $bitacoraService)
    {
=======
    public function __construct(
     VoteTallyService $voteTallyService,
     ElectionService $electionService,
     InstitutionService $institutionService,
     BitacoraService $bitacoraService
    ){
>>>>>>> 28a1fc9 (feat: integración de servicios esenciales y corrección de constructores)
        $this->voteTallyService = $voteTallyService;
        $this->electionService = $electionService;
        $this->institutionService = $institutionService;
        $this->bitacoraService = $bitacoraService;
    }

    public function resultados()
    {
<<<<<<< HEAD
        $datos = $this->electionService->resultadosCompletos();
        return response()->json(['success' => true, ...$datos]);
=======
        try {
            $ganador = $this->voteTallyService->verificarGanador();

            $this->bitacoraService->registrar('Verificación de ganador', 'Se verificó el ganador de la elección.');

            return view('admin.resultados', compact('ganador'));
        } catch (Exception $e) {
            return back()->withErrors('Error al cargar los resultados: ' . $e->getMessage());
        }
>>>>>>> 28a1fc9 (feat: integración de servicios esenciales y corrección de constructores)
    }

    public function verificarGanadorAutomatico()
    {
<<<<<<< HEAD
        return response()->json(['success' => true, ...$this->electionService->verificarGanador()]);
=======
        try {
            $ganador = $this->voteTallyService->verificarGanador();

            $this->bitacoraService->registrar('Verificación de ganador automático', 'Se verificó automáticamente el ganador de la elección.');

           return redirect()->back()->with('success', 'Ganador verificado y procesado correctamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al verificar el ganador automáticamente: ' . $e->getMessage());
        }
>>>>>>> 28a1fc9 (feat: integración de servicios esenciales y corrección de constructores)
    }

    public function apiVerificarGanador()
    {
<<<<<<< HEAD
        return response()->json(['success' => true, ...$this->electionService->verificarGanador()]);
=======
        try {
            $ganador = $this->voteTallyService->verificarGanador();

            $this->bitacoraService->registrar('Verificación de ganador API', 'Se verificó el ganador de la elección a través de la API.');

            return response()->json([
                'success' => true,
                'ganador' => $ganador
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar el ganador a través de la API: ' . $e->getMessage()
            ], 500);
        }
>>>>>>> 28a1fc9 (feat: integración de servicios esenciales y corrección de constructores)
    }

    public function exportarResultadosCsv()
    {
<<<<<<< HEAD
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
=======
        try {
            $this->bitacoraService->registrar('Exportación de resultados CSV', 'Se exportaron los resultados de la elección a CSV.');

            $filename = 'resultados_eleccion_' . date('Y-m-d_H-i-s') . '.csv';
            $resultados = $this->voteTallyService->obtenerResultados();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use ($resultados) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Candidato / Opción', 'Total Votos', 'Porcentaje']);

                foreach ($resultados as $row) {
                   fputcsv($file, [
                        $row->id ?? '', 
                        $row->nombre ?? 'N/D', 
                        $row->votos ?? 0, 
                        ($row->porcentaje ?? 0) . '%'
                    ]);
                }

                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            return back()->withErrors('Error al exportar los resultados a CSV: ' . $e->getMessage());
        }
>>>>>>> 28a1fc9 (feat: integración de servicios esenciales y corrección de constructores)
    }
}
