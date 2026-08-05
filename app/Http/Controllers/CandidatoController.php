<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatoRequest;
use App\Models\Candidato;
use App\Models\Puesto;
use App\Services\BitacoraService;
use Exception;
use Illuminate\Http\Request;

class CandidatoController extends Controller
{
    private BitacoraService $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }

    public function index(Request $request)
    {
        try {
            $candidatos = Candidato::with('puesto', 'party')->get();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $candidatos,
                ], 200);
            }

            return view('candidatos.index', compact('candidatos'));
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar los candidatos: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withErrors('Error al cargar los candidatos: ' . $e->getMessage());
        }
    }

    public function storePuesto(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
            ]);

            $puesto = Puesto::create($request->all());

            $this->bitacoraService->registrar('Creación de puesto', 'Se creó un nuevo puesto: ' . $puesto->nombre);

            return redirect()->route('admin.candidatos')->with('success', 'Puesto creado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al crear el puesto: ' . $e->getMessage())->withInput();
        }
    }

    public function editPuesto($id)
    {
        try {
            $puesto = Puesto::findOrFail($id);

            return view('puestos.edit', compact('puesto'));
        } catch (Exception $e) {
            return redirect()->route('admin.candidatos')->withErrors('Puesto no encontrado: ' . $e->getMessage())->withInput();
        }
    }

    public function updatePuesto(Request $request, $id)
    {
        try {
            $puesto = Puesto::findOrFail($id);
            $request->validate([
                'nombre' => 'required|string|max:255',
            ]);
            $puesto->update($request->all());

            $this->bitacoraService->registrar('Actualización de puesto', 'Se actualizó el puesto ID: ' . $id);

            return redirect()->route('admin.candidatos')->with('success', 'Puesto actualizado exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error al actualizar el puesto: ' . $e->getMessage());
        }
    }

    public function destroyPuesto($id)
    {
        try {
            $puesto = Puesto::findOrFail($id);
            $puesto->delete();

            $this->bitacoraService->registrar('Eliminación de puesto', 'Se eliminó el puesto ID: ' . $id);

            return redirect()->back()->with('success', 'Puesto eliminado exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error al eliminar el puesto: ' . $e->getMessage());
        }
    }

    public function storeCandidato(StoreCandidatoRequest $request)
    {
        try {
            $candidato = Candidato::create($request->validated());

            $this->bitacoraService->registrar('Creación de candidato', 'Se creó un nuevo candidato ID: ' . $candidato->id);

            return redirect()->back()->with('success', 'Candidato creado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al crear el candidato: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyCandidato($id)
    {
        try {
            $candidato = Candidato::findOrFail($id);
            $candidato->delete();

            $this->bitacoraService->registrar('Eliminación de candidato', 'Se eliminó el candidato ID: ' . $id);

            return redirect()->back()->with('success', 'Candidato eliminado exitosamente.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Error al eliminar el candidato: ' . $e->getMessage());
        }
    }
}
