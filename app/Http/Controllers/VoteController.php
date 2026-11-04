<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\UniqueConstraintViolationException;
use App\Models\Student;
use App\Models\Vote;
use App\Models\Urna;
use App\Services\BitacoraService;

class VoteController extends Controller
{
    private BitacoraService $bitacora;

    public function __construct(BitacoraService $bitacora)
    {
        $this->bitacora = $bitacora;
    }

    public function store(Request $request)
    {
        $request->validate([
            'identificacion' => 'required|string',
            'party_id' => 'required|integer|min:0',
        ]);

        $user = $request->user();

        try {
            [$status, $message] = DB::transaction(function () use ($request, $user) {
                $estudiante = Student::where('identificacion', $request->identificacion)
                    ->lockForUpdate()
                    ->first();

                if (!$estudiante) {
                    return [404, 'Estudiante no encontrado'];
                }

                if ($estudiante->voto) {
                    return [409, 'Este estudiante ya votó'];
                }

                $mesaId = null;

                if ($user && $user->isJrv()) {
                    if (!$user->mesa_id) {
                        return [403, 'Su usuario no tiene una mesa asignada'];
                    }
                    $mesaId = $user->mesa_id;
                }

                $urna = Urna::where('id_estudiante', $estudiante->id)
                    ->when($mesaId, fn ($q) => $q->where('id_mesa', $mesaId))
                    ->lockForUpdate()
                    ->first();

                if (!$urna) {
                    $urna = Urna::where('estado', 1)
                        ->when($mesaId, fn ($q) => $q->where('id_mesa', $mesaId))
                        ->lockForUpdate()
                        ->first();
                }

                if (!$urna) {
                    return [400, 'No hay una urna activa para su mesa'];
                }

                Vote::create([
                    'student_id' => $estudiante->id,
                    'encrypted_party' => Crypt::encryptString((string) $request->party_id),
                    'id_mesa' => $urna->id_mesa,
                ]);

                $estudiante->voto = true;
                $estudiante->save();

                // Libera la urna que atendió a este estudiante
                Urna::where('id_estudiante', $estudiante->id)
                    ->update(['id_estudiante' => null, 'estado' => 1]);

                return [201, 'Voto registrado correctamente'];
            });
        } catch (UniqueConstraintViolationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Este estudiante ya votó',
            ], 409);
        }

        if ($status === 201) {
            $this->bitacora->registrar('emisión de voto', 'Mesa ' . $user?->mesa_id);
        }

        return response()->json([
            'success' => $status === 201,
            'message' => $message,
        ], $status);
    }
}
