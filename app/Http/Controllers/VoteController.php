<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Student;
use App\Models\Vote;
use App\Models\Urna;

class VoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'identificacion' => 'required|string',
            'party_id' => 'required|integer',
        ]);

        $estudiante = Student::where('identificacion', $request->identificacion)->first();

        if (!$estudiante) {
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
        }

        if ($estudiante->voto) {
            return response()->json(['success' => false, 'message' => 'Este estudiante ya votó'], 409);
        }

        $urna = Urna::where('estado', 1)->first();

        if (!$urna) {
            return response()->json(['success' => false, 'message' => 'No hay una urna activa'], 400);
        }

        $votoCifrado = Crypt::encryptString((string) $request->party_id);

        Vote::create([
            'encrypted_party' => $votoCifrado,
            'id_mesa' => $urna->id_mesa,
        ]);

        $estudiante->voto = true;
        $estudiante->save();

        return response()->json(['success' => true, 'message' => 'Voto registrado correctamente'], 201);
    }
}
