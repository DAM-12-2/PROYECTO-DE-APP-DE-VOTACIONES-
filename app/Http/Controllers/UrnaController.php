<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrnaRequest;
use App\Http\Requests\UpdateUrnaRequest;
use App\Models\Mesa;
use App\Models\Urna;
use App\Services\BitacoraService;
use App\Services\UrnaService;
use Illuminate\Http\Request;
use Exception;

class UrnaController extends Controller
{
    private BitacoraService $bitacoraService;
    private UrnaService $urnaService;

    public function __construct(BitacoraService $bitacoraService, UrnaService $urnaService)
    {
        $this->bitacoraService = $bitacoraService;
        $this->urnaService = $urnaService;
    }

    public function index()
    {
        $urnas = Urna::with('mesa')->get();
        return view('admin.urnas', compact('urnas'));
    }

    public function create()
    {
        $mesas = Mesa::all();
        return view('admin.urnas_edit', compact('mesas'));
    }

    public function store(StoreUrnaRequest $request)
    {
        try {
            Urna::create($request->validated());
            $this->bitacoraService->registrar('creación de urna', 'Se creó una nueva urna');

            return redirect()->route('admin.urnas')->with('success', 'Urna creada exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al crear la urna: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $urna = Urna::findOrFail($id);
        $mesas = Mesa::all();
        return view('admin.urnas_edit', compact('urna', 'mesas'));
    }

    public function update(UpdateUrnaRequest $request, $id)
    {
        try {
            $urna = Urna::findOrFail($id);
            $urna->update($request->validated());
            $this->bitacoraService->registrar('actualización de urna', 'Se actualizó la urna ID: ' . $id);

            return redirect()->route('admin.urnas')->with('success', 'Urna actualizada exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al actualizar la urna: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $urna = Urna::findOrFail($id);
            $urna->delete();
            $this->bitacoraService->registrar('eliminación de urna', 'Se eliminó la urna ID: ' . $id);

            return redirect()->route('admin.urnas')->with('success', 'Urna eliminada exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar la urna: ' . $e->getMessage());
        }
    }

    public function activar(Request $request)
    {
        try {
            $id = (int) $request->input('id') ?? 0;
            $idEstudiante = (int) $request->input('id_estudiante') ?? 0;
            $result = $this->urnaService->activar($id, $idEstudiante);

            $this->bitacoraService->registrar('activación de urna', 'Se activó la urna ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Urna activada.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al activar la urna: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function desactivar(Request $request)
    {
        try {
            $id = (int) $request->input('id') ?? 0;
            $result = $this->urnaService->desactivar($id);

            $this->bitacoraService->registrar('desactivación de urna', 'Se desactivó la urna ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Urna desactivada.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desactivar la urna: ' . $e->getMessage(),
            ], 500);
        }
    }
}
