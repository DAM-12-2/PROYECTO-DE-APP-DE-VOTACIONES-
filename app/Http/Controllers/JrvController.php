<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UrnaService;
use App\Services\StudentSearchService;

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
        $identificacion = $request->query('identificacion');

        if (!$identificacion) {
            return response()->json(['success' => false, 'message' => 'Debe enviar una identificación'], 400);
        }

        $estudiante = $this->studentSearchService->buscar($identificacion);

        if (!$estudiante) {
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'estudiante' => [
                'id' => $estudiante->id,
                'identificacion' => $estudiante->identificacion,
                'nombre' => $estudiante->nombre,
                'apellidos' => $estudiante->apellidos,
                'seccion' => $estudiante->seccion,
                'voto' => $estudiante->voto,
            ],
        ]);
    }

    public function activarUrna(Request $request)
    {
        $request->validate(['codigo' => 'required|string']);

        try {
            $urna = $this->urnaService->activar($request->codigo);
            return response()->json(['success' => true, 'message' => 'Urna activada', 'urna' => $urna]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Urna no encontrada'], 404);
        }
    }

    public function desactivarUrna(Request $request)
    {
        $request->validate(['codigo' => 'required|string']);

        try {
            $urna = $this->urnaService->desactivar($request->codigo);
            return response()->json(['success' => true, 'message' => 'Urna desactivada', 'urna' => $urna]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Urna no encontrada'], 404);
        }
    }
}
