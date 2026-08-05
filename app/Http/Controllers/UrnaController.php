<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrnaRequest;
use App\Http\Requests\UpdateUrnaRequest;
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
        $urnas = $this->urnaService->getAllUrnas();
        return view('urnas.index', compact('urnas'));
    }

    public function store(StoreUrnaRequest $request)
    {
        try {
            $urna = $this->urnaService->createUrna($request->validated());

            $this->bitacoraService->registrar('Creación de urna', 'Se creó una nueva urna ID: ' . $urna->id);

            return redirect()->route('urnas.index')->with('success', 'Urna eliminada exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar la urna: ' . $e->getMessage());

        }
    }

    public function edit($id)
    {
        $urna = $this->urnaService->getUrnaById($id);
        return view('urnas.edit', compact('urna'));
    }

    public function update(UpdateUrnaRequest $request, $id)
    {
        try {
            $this->urnaService->actualizarUrna($id, $request->validated());
            
            $this->bitacoraService->registrar('Actualización de urna', 'Se actualizó la urna ID: ' . $id);

            return redirect()->route('urnas.index')->with('success', 'Urna actualizada exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al actualizar la urna: ' . $e->getMessage())->withInput();
        }

    }

    public function destroy($id)
    {
        try {
            $this->urnaService->eliminarUrna($id);

            $this->bitacoraService->registrar('Eliminación de urna', 'Se eliminó la urna ID: ' . $id);

            return redirect()->route('urnas.index')->with('success', 'Urna eliminada exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar la urna: ' . $e->getMessage());
        }
    }

    public function activar(Request $request)
    {
        try {
            $id = $request->input('id') ?? $request->input('id');
            $this->urnaService->activar($id);

            $this->bitacoraService->registrar('Activación de urna', 'Se activó la urna ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Urna activada exitosamente.'
            ],200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al activar la urna: ' . $e->getMessage()
            ], 500);
        }
    }

    public function desactivar(Request $request)
    {
        try {
            $id = $request->input('id') ?? $request->input('id');
            $this->urnaService->desactivar($id);

            $this->bitacoraService->registrar('Desactivación de urna', 'Se desactivó la urna ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Urna desactivada exitosamente.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al desactivar la urna: ' . $e->getMessage()
            ], 500);
        }
    }
}
