<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Models\Party;
use App\Services\BitacoraService;
use App\Services\FileUploadService;
use Exception;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    private BitacoraService $bitacoraService;
    private FileUploadService $fileUploadService;

    public function __construct(BitacoraService $bitacoraService, FileUploadService $fileUploadService)
    {
        $this->bitacoraService = $bitacoraService;
        $this->fileUploadService = $fileUploadService;
    }

    public function index(Request $request)
    {
        try {
            $parties = Party::all();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $parties,
                ], 200);
            }

            return view('admin.parties', compact('parties'));
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar los partidos: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withErrors('Error al cargar los partidos: ' . $e->getMessage());
        }
    }

    public function store(StorePartyRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('logo')) {
                $data['logo'] = $this->fileUploadService->uploadPartyImage($request->file('logo'), 'logos');
            }

            $party = Party::create($data);

            $this->bitacoraService->registrar('Creación de partido', 'Se creó un nuevo partido ID: ' . $party->id);

            return redirect()->route('admin.parties')->with('success', 'Partido creado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al crear el partido: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $party = Party::findOrFail($id);

            return view('admin.parties_edit', compact('party'));
        } catch (Exception $e) {
            return redirect()->route('admin.parties')->withErrors('Partido no encontrado: ' . $e->getMessage());
        }
    }

    public function update(UpdatePartyRequest $request, $id)
    {
        try {
            $party = Party::findOrFail($id);
            $data = $request->validated();

            if ($request->hasFile('logo')) {
                $data['logo'] = $this->fileUploadService->uploadPartyImage($request->file('logo'), 'logos');
            }

            $party->update($data);

            $this->bitacoraService->registrar('Actualización de partido', 'Se actualizó el partido ID: ' . $party->id);

            return redirect()->route('admin.parties')->with('success', 'Partido actualizado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al actualizar el partido: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $party = Party::findOrFail($id);
            $party->delete();

            $this->bitacoraService->registrar('Eliminación de partido', 'Se eliminó el partido ID: ' . $party->id);

            return redirect()->route('admin.parties')->with('success', 'Partido eliminado exitosamente.');
        } catch (Exception $e) {
            return back()->withErrors('Error al eliminar el partido: ' . $e->getMessage());
        }
    }
}
