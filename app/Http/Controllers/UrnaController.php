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
            $urna = Urna::create($request->validated());
            $this->bitacoraService->registrar('creación de urna', 'Se creó la urna ID: ' . $urna->id);

            return response()->json([
                'success' => true,
                'message' => 'Urna creada exitosamente.',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la urna: ' . $e->getMessage(),
            ], 500);
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

            return response()->json([
                'success' => true,
                'message' => 'Urna actualizada exitosamente.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la urna: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $urna = Urna::findOrFail($id);
            $urna->delete();
            $this->bitacoraService->registrar('eliminación de urna', 'Se eliminó la urna ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Urna eliminada exitosamente.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la urna: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function activar(Request $request)
    {
        try {
            $result = $this->urnaService->activar($request->input('codigo'));
            return response()->json($result, $result['success'] ? 200 : 400);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al activar la urna: ' . $e->getMessage()], 500);
        }
    }

    public function desactivar(Request $request)
    {
        try {
            $result = $this->urnaService->desactivar($request->input('codigo'));
            return response()->json($result, $result['success'] ? 200 : 400);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al desactivar la urna: ' . $e->getMessage()], 500);
        }
    }
}
