<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMesaRequest;
use App\Http\Requests\UpdateMesaRequest;
use App\Models\Mesa;
use App\Models\SeccionMesa;
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

    public function index()
    {
        $mesas = Mesa::with(['secciones', 'miembros.student', 'miembros.party'])->get();

        return view('admin.mesas', compact('mesas'));
    }

    public function store(StoreMesaRequest $request)
    {
        Mesa::create([
            'nombre' => $request->numero,
            'ubicacion' => $request->ubicacion ?? '',
            'estado' => 1,
        ]);

        return redirect()->route('admin.mesas')->with('success', 'Mesa creada exitosamente.');
    }

    public function edit($id)
    {
        $mesa = Mesa::findOrFail($id);
        return view('admin.mesas_edit', compact('mesa'));
    }

    public function update(UpdateMesaRequest $request, $id)
    {
        $mesa = Mesa::findOrFail($id);

        $data = [];
        if ($request->has('numero')) {
            $data['nombre'] = $request->numero;
        }
        if ($request->has('ubicacion')) {
            $data['ubicacion'] = $request->ubicacion;
        }

        $mesa->update($data);

        return redirect()->route('admin.mesas')->with('success', 'Mesa actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $mesa = Mesa::findOrFail($id);
        $mesa->secciones()->delete();
        $mesa->miembros()->delete();
        $mesa->delete();

        return redirect()->route('admin.mesas')->with('success', 'Mesa eliminada exitosamente.');
    }

    public function storeSeccion(Request $request, $id)
    {
        $request->validate([
            'seccion' => 'required|string|max:20',
        ]);

        $mesa = Mesa::findOrFail($id);

        $mesa->secciones()->create([
            'seccion' => $request->seccion,
        ]);

        return redirect()->route('admin.mesas')->with('success', 'Sección agregada exitosamente.');
    }

    public function destroySeccion($id)
    {
        $seccion = SeccionMesa::findOrFail($id);
        $seccion->delete();

        return redirect()->route('admin.mesas')->with('success', 'Sección eliminada exitosamente.');
    }

    public function moverSeccion(Request $request)
    {
        $request->validate([
            'seccion_id' => 'required|exists:secciones_mesa,id',
            'mesa_id' => 'required|exists:mesas,id',
        ]);

        $seccion = SeccionMesa::findOrFail($request->seccion_id);
        $seccion->update(['mesa_id' => $request->mesa_id]);

        return response()->json([
            'success' => true,
            'message' => 'Sección movida exitosamente.'
        ]);
    }
}
