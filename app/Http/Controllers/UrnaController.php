<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrnaRequest;
use App\Http\Requests\UpdateUrnaRequest;
use App\Models\Mesa;
use App\Models\Urna;
use App\Services\BitacoraService;
use App\Services\UrnaService;
use Illuminate\Http\Request;

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
        $urnas = Urna::with('student')->orderBy('codigo')->get();

        return view('admin.urnas', compact('urnas'));
    }

    public function store(StoreUrnaRequest $request)
    {
        Urna::create($request->validated());
        $this->bitacoraService->registrar('crear_urna', "Urna {$request->codigo} registrada");

        return back()->with('success', 'Terminal creada correctamente.');
    }

    public function edit($id)
    {
        $urna = Urna::findOrFail($id);
        $mesas = Mesa::orderBy('numero')->get();

        return view('admin.urnas_edit', compact('urna', 'mesas'));
    }

    public function update(UpdateUrnaRequest $request, $id)
    {
        $urna = Urna::findOrFail($id);
        $urna->update($request->validated());
        $this->bitacoraService->registrar('editar_urna', "Urna {$urna->codigo} actualizada");

        return redirect()->route('admin.urnas')->with('success', 'Terminal actualizada correctamente.');
    }

    public function destroy($id)
    {
        $urna = Urna::findOrFail($id);
        $urna->delete();
        $this->bitacoraService->registrar('eliminar_urna', "Urna eliminada");

        return back()->with('success', 'Terminal eliminada correctamente.');
    }

    public function activar(Request $request)
    {
        $request->validate([
            'idUrna' => 'required|integer|exists:urnas,id',
            'idEstudiante' => 'required|integer|exists:students,id',
        ]);

        $result = $this->urnaService->activar($request->idUrna, $request->idEstudiante);

        return response()->json($result);
    }

    public function desactivar(Request $request)
    {
        $request->validate(['idUrna' => 'required|integer|exists:urnas,id']);

        $result = $this->urnaService->desactivar($request->idUrna);

        return response()->json($result);
    }
}
