<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Services\StudentSearchService;
use App\Services\UrnaService;
use Illuminate\Http\Request;
use Exception;

class JrvController extends Controller
{
    private UrnaService $urnaService;
    private StudentSearchService $studentSearchService;

    public function __construct(UrnaService $urnaService, StudentSearchService $studentSearchService)
    {
        $this->urnaService = $urnaService;
        $this->studentSearchService = $studentSearchService;
    }

    public function index()
    {
        return view('jrv.index');
    }

    public function searchStudents(Request $request)
    {
        $identificacion = $request->query('identificacion') ?? $request->input('identificacion');

        if (!$identificacion) {
            return response()->json(['success' => false, 'message' => 'Debe enviar una identificación'], 400);
        }

        $estudiante = $this->studentSearchService->buscar($identificacion);

        if (!$estudiante) {
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [[
                'id' => $estudiante->id,
                'identificacion' => $estudiante->identificacion,
                'nombre' => $estudiante->nombre,
                'apellidos' => $estudiante->apellidos,
                'seccion' => $estudiante->seccion,
                'voto' => $estudiante->voto,
            ]],
        ], 200);
    }

    public function partidos()
    {
        $partidos = Party::where('estado', true)->get(['id', 'siglas', 'nombre']);

        return response()->json([
            'success' => true,
            'data' => $partidos,
        ], 200);
    }

    public function activarUrna(Request $request)
    {
        try {
            $result = $this->urnaService->activar($request->input('codigo'));
            return response()->json($result, $result['success'] ? 200 : 400);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al activar la urna: ' . $e->getMessage()], 500);
        }
    }

    public function desactivarUrna(Request $request)
    {
        try {
            $result = $this->urnaService->desactivar($request->input('codigo'));
            return response()->json($result, $result['success'] ? 200 : 400);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al desactivar la urna: ' . $e->getMessage()], 500);
        }
    }
}
