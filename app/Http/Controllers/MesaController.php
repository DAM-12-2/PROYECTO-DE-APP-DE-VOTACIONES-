<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMesaRequest;
use App\Http\Requests\UpdateMesaRequest;
use App\Models\Mesa;
use App\Services\BitacoraService;
use Exception;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    private BitacoraService $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }

    public function index(Request $request)
    {
        try {
            $mesas = Mesa::with('secciones', 'miembros', 'incidentes')->get();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $mesas,
                ], 200);
            }

            return view('mesas.index', compact('mesas'));
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar las mesas: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withErrors('Error al cargar las mesas: ' . $e->getMessage());
        }
    }

    public function store(StoreMesaRequest $request)
    {
        try {
            $mesa = Mesa::create($request->validated());
            $this->bitacoraService->registrar('Creación de mesa', 'Se creó una nueva mesa ID: ' . $mesa->id);

            return redirect()->back()->with('success', 'Mesa creada exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al crear la mesa: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $mesa = Mesa::findOrFail($id);

            return view('mesas.edit', compact('mesa'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Mesa no encontrada: ' . $e->getMessage());
        }
    }

    public function update(UpdateMesaRequest $request, $id)
    {
        try {
            $mesa = Mesa::findOrFail($id);
            $mesa->update($request->validated());
            $this->bitacoraService->registrar('Actualización de mesa', 'Se actualizó la mesa ID: ' . $id);

            return redirect()->back()->with('success', 'Mesa actualizada exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al actualizar la mesa: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $mesa = Mesa::findOrFail($id);
            $mesa->delete();
            $this->bitacoraService->registrar('Eliminación de mesa', 'Se eliminó la mesa ID: ' . $id);

            return redirect()->back()->with('success', 'Mesa eliminada exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar la mesa: ' . $e->getMessage());
        }
    }

    public function storeSeccion(Request $request, $id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Pendiente de implementación.',
        ], 200);
    }

    public function destroySeccion($id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Pendiente de implementación.',
        ], 200);
    }

    public function moverSeccion(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Pendiente de implementación.',
        ], 200);
    }
}
