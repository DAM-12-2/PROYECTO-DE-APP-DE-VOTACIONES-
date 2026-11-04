<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Student;
use App\Models\Urna;
use App\Services\StudentSearchService;
use App\Services\UrnaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $mesa = \App\Models\Mesa::find(auth()->user()->mesa_id);
        $eleccionAbierta = \App\Models\Setting::where('nombre', 'eleccion_abierta')->value('detalle') === '1';

        return view('jrv.index', compact('mesa', 'eleccionAbierta'));
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

        $mesaId = auth()->user()->mesa_id;
        $pertenece = \App\Models\SeccionMesa::where('seccion', $estudiante->seccion)
            ->where('mesa_id', $mesaId)
            ->exists();

        if (!$pertenece) {
            return response()->json([
                'success' => false,
                'message' => 'El estudiante no pertenece a su mesa.',
            ], 404);
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

    public function urnas()
    {
        $urnas = Urna::where('id_mesa', auth()->user()->mesa_id)
            ->with('estudiante')
            ->orderBy('codigo')
            ->get()
            ->map(fn (Urna $urna) => [
                'id' => $urna->id,
                'codigo' => $urna->codigo,
                'estado' => $urna->estado,
                'estudiante' => $urna->estudiante ? [
                    'identificacion' => $urna->estudiante->identificacion,
                    'nombre' => trim($urna->estudiante->nombre . ' ' . $urna->estudiante->apellidos),
                ] : null,
            ]);

        return response()->json(['success' => true, 'data' => $urnas]);
    }

    public function asignar(Request $request)
    {
        $data = $request->validate([
            'urna_id' => 'required|integer',
            'identificacion' => 'required|string',
        ]);

        $mesaId = auth()->user()->mesa_id;

        [$status, $payload] = DB::transaction(function () use ($data, $mesaId) {
            $urna = Urna::where('id', $data['urna_id'])
                ->where('id_mesa', $mesaId)
                ->lockForUpdate()
                ->first();

            if (!$urna) {
                return [404, ['success' => false, 'message' => 'La urna no pertenece a su mesa.']];
            }

            $estudiante = Student::where('identificacion', $data['identificacion'])
                ->lockForUpdate()
                ->first();

            if (!$estudiante) {
                return [404, ['success' => false, 'message' => 'Estudiante no encontrado.']];
            }

            if ($estudiante->voto) {
                return [409, ['success' => false, 'message' => 'Este estudiante ya votó.']];
            }

            if ($urna->id_estudiante) {
                return [409, ['success' => false, 'message' => 'La urna ya tiene un estudiante asignado.']];
            }

            $urna->update([
                'id_estudiante' => $estudiante->id,
                'estado' => 2,
            ]);

            return [200, [
                'success' => true,
                'message' => 'Estudiante asignado a la urna.',
                'data' => [
                    'urna' => $urna->codigo,
                    'estudiante' => trim($estudiante->nombre . ' ' . $estudiante->apellidos),
                ],
            ]];
        });

        return response()->json($payload, $status);
    }

    public function liberar(Request $request)
    {
        $data = $request->validate(['urna_id' => 'required|integer']);

        $urna = Urna::where('id', $data['urna_id'])
            ->where('id_mesa', auth()->user()->mesa_id)
            ->first();

        if (!$urna) {
            return response()->json(['success' => false, 'message' => 'La urna no pertenece a su mesa.'], 404);
        }

        $urna->update(['id_estudiante' => null, 'estado' => 1]);

        return response()->json(['success' => true, 'message' => 'Urna liberada.']);
    }
}
