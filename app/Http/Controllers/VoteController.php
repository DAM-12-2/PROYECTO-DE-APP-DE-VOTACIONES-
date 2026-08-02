<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class VoteController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'identificacion' => 'required|string',
        ]);

        $estudiante = Student::where('identificacion', $request->input('identificacion'))->first();

        if (!$estudiante) {
            return response()->json([
                'success' => false,
                'message' => 'Estudiante no encontrado'
            ], 404);
        }

        if ($estudiante->voto) {
            return response()->json([
                'success' => false,
                'message' => 'El estudiante ya ha votado'
            ], 400);
        }
        $estudiante->voto = true;
        $estudiante->save();

        return response()->json([
            'success' => true,
            'message' => 'voto registrado correctamente',
        ], 201);
    }
}