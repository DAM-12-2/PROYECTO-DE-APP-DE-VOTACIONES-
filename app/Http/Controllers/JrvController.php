<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Urna;
use App\Services\StudentSearchService;
use App\Services\UrnaService;
use Exception;
use Illuminate\Http\Request;

class JrvController extends Controller
{
    private UrnaService $urnaService;
    private StudentSearchService $searchService;

    public function __construct(UrnaService $urnaService, StudentSearchService $searchService)
    {
        $this->urnaService = $urnaService;
        $this->searchService = $searchService;
    }

    public function index()
    {
        return view('jrv.index');
    }

    public function searchStudents(Request $request)
    {
        try {
            $query = (string) $request->input('query', '');
            $mesaId = $request->input('mesa_id');
            $students = $this->searchService->search($query, $mesaId ? (int) $mesaId : null, true);

            return response()->json([
                'success' => true,
                'data' => $students,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar estudiantes: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function activarUrna(Request $request)
    {
        try {
            $idUrna = (int) $request->input('id_urna', $request->input('id', 0));
            $idEstudiante = (int) $request->input('id_estudiante', 0);
            $result = $this->urnaService->activar($idUrna, $idEstudiante);

            return response()->json($result, $result['success'] ? 200 : 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al activar la urna: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function desactivarUrna(Request $request)
    {
        try {
            $idUrna = (int) $request->input('id_urna', $request->input('id', 0));
            $result = $this->urnaService->desactivar($idUrna);

            return response()->json($result, $result['success'] ? 200 : 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desactivar la urna: ' . $e->getMessage(),
            ], 500);
        }
    }
}
