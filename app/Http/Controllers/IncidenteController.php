<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncidenteRequest;
use App\Models\Incidente;
use App\Services\BitacoraService;
use Exception;
use Illuminate\Http\Request;

class IncidenteController extends Controller
{
    private BitacoraService $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }

    public function index(Request $request)
    {
        try {
            $incidentes = Incidente::with('mesa', 'user')->get();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $incidentes,
                ], 200);
            }

            return view('incidentes.index', compact('incidentes'));
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar los incidentes: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withErrors('Error al cargar los incidentes: ' . $e->getMessage());
        }
    }

    public function store(StoreIncidenteRequest $request)
    {
        try {
            $incidente = Incidente::create($request->validated());
            $this->bitacoraService->registrar('Registro de incidente', 'Se registró un incidente ID: ' . $incidente->id);

            return redirect()->back()->with('success', 'Incidente registrado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al registrar el incidente: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $incidente = Incidente::findOrFail($id);
            $incidente->delete();
            $this->bitacoraService->registrar('Eliminación de incidente', 'Se eliminó el incidente ID: ' . $id);

            return redirect()->back()->with('success', 'Incidente eliminado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar el incidente: ' . $e->getMessage());
        }
    }
}
