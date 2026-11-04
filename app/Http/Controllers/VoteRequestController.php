<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\SeccionMesa;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Urna;
use App\Models\VoteRequest;
use App\Events\VoteRequestApproved;
use App\Events\VoteRequestCreated;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VoteRequestController extends Controller
{
    private BitacoraService $bitacora;

    public function __construct(BitacoraService $bitacora)
    {
        $this->bitacora = $bitacora;
    }

    public function registro()
    {
        return view('registro.index', [
            'mesa' => Mesa::find(auth()->user()->mesa_id),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'identificacion' => 'required|string|max:30',
        ]);

        if (Setting::where('nombre', 'eleccion_abierta')->value('detalle') !== '1') {
            return response()->json(['success' => false, 'message' => 'La votación está cerrada.'], 423);
        }

        [$status, $payload] = DB::transaction(function () use ($data, $request) {
            $estudiante = Student::where('identificacion', $data['identificacion'])
                ->lockForUpdate()
                ->first();

            if (!$estudiante) {
                return [404, ['success' => false, 'message' => 'Estudiante no encontrado en el padrón.']];
            }

            if ($estudiante->voto) {
                return [409, ['success' => false, 'message' => 'Este estudiante ya votó.']];
            }

            $existente = VoteRequest::where('student_id', $estudiante->id)->first();
            if ($existente) {
                return [200, [
                    'success' => true,
                    'message' => 'Ya existe una solicitud para este estudiante.',
                    'data' => $this->payload($existente),
                ]];
            }

            $mesaId = $this->mesaPara($estudiante, $request->user());

            if ($request->user()->isJrv() && (int) $request->user()->mesa_id !== $mesaId) {
                return [403, ['success' => false, 'message' => 'El estudiante pertenece a otra mesa.']];
            }

            $solicitud = VoteRequest::create([
                'student_id' => $estudiante->id,
                'mesa_id' => $mesaId,
                'estado' => VoteRequest::PENDIENTE,
                'created_by' => $request->user()->id,
            ]);
            $this->bitacora->registrar('solicitud de voto', $estudiante->identificacion);

            return [201, [
                'success' => true,
                'message' => 'Solicitud enviada a la JRV.',
                'data' => $this->payload($solicitud),
            ]];
        });

        if ($status === 201) {
            $this->emitir(new VoteRequestCreated(VoteRequest::with('student')->find($payload['data']['id'])));
        }

        return response()->json($payload, $status);
    }

    public function estado(int $id)
    {
        $solicitud = VoteRequest::with('urna', 'student')->find($id);

        if (!$solicitud) {
            return response()->json(['success' => false, 'message' => 'Solicitud no encontrada.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->payload($solicitud),
        ]);
    }

    public function cola()
    {
        $mesaId = auth()->user()->mesa_id;

        $solicitudes = VoteRequest::with('student', 'urna')
            ->where('mesa_id', $mesaId)
            ->whereIn('estado', [VoteRequest::PENDIENTE, VoteRequest::APROBADA])
            ->orderBy('created_at')
            ->get()
            ->map(fn (VoteRequest $s) => $this->payload($s));

        return response()->json(['success' => true, 'data' => $solicitudes]);
    }

    public function aprobar(Request $request, int $id)
    {
        $data = $request->validate([
            'urna_id' => 'required|integer',
        ]);

        $mesaId = auth()->user()->mesa_id;

        [$status, $payload] = DB::transaction(function () use ($data, $id, $mesaId, $request) {
            $solicitud = VoteRequest::where('id', $id)
                ->where('mesa_id', $mesaId)
                ->lockForUpdate()
                ->first();

            if (!$solicitud) {
                return [404, ['success' => false, 'message' => 'Solicitud no encontrada para su mesa.']];
            }

            if ($solicitud->estado !== VoteRequest::PENDIENTE) {
                return [409, ['success' => false, 'message' => 'La solicitud ya fue procesada.']];
            }

            $urna = Urna::where('id', $data['urna_id'])
                ->where('id_mesa', $mesaId)
                ->lockForUpdate()
                ->first();

            if (!$urna) {
                return [404, ['success' => false, 'message' => 'La urna no pertenece a su mesa.']];
            }

            if ($urna->id_estudiante) {
                return [409, ['success' => false, 'message' => 'La urna ya está ocupada.']];
            }

            $solicitud->update([
                'estado' => VoteRequest::APROBADA,
                'urna_id' => $urna->id,
                'approved_by' => $request->user()->id,
                'approved_at' => now(),
            ]);

            $urna->update([
                'id_estudiante' => $solicitud->student_id,
                'estado' => 2,
            ]);

            $this->bitacora->registrar('aprobación de solicitud', 'Solicitud ' . $solicitud->id);

            return [200, [
                'success' => true,
                'message' => 'Solicitud aprobada. El estudiante puede pasar a la urna.',
                'data' => $this->payload($solicitud->fresh()),
            ]];
        });

        if ($status === 200) {
            $this->emitir(new VoteRequestApproved(VoteRequest::with('student', 'urna')->find($payload['data']['id'])));
        }

        return response()->json($payload, $status);
    }

    public function rechazar(int $id)
    {
        $mesaId = auth()->user()->mesa_id;

        $solicitud = VoteRequest::where('id', $id)->where('mesa_id', $mesaId)->first();

        if (!$solicitud) {
            return response()->json(['success' => false, 'message' => 'Solicitud no encontrada.'], 404);
        }

        $identificacion = $solicitud->student?->identificacion;
        $solicitud->delete();

        $this->bitacora->registrar('rechazo de solicitud', (string) $identificacion);

        return response()->json(['success' => true, 'message' => 'Solicitud rechazada.']);
    }

    private function mesaPara(Student $estudiante, $usuario): int
    {
        $mesaId = SeccionMesa::where('seccion', $estudiante->seccion)->value('mesa_id');

        if ($mesaId) {
            return (int) $mesaId;
        }

        if ($usuario?->mesa_id) {
            return (int) $usuario->mesa_id;
        }

        $primera = Mesa::where('estado', 1)->orderBy('id')->value('id');

        if (!$primera) {
            throw new \RuntimeException('No hay mesas activas configuradas.');
        }

        return (int) $primera;
    }

    private function emitir(object $evento): void
    {
        try {
            broadcast($evento);
        } catch (\Throwable $e) {
            Log::warning('No se pudo emitir el evento en tiempo real: ' . $e->getMessage());
        }
    }

    private function payload(VoteRequest $solicitud): array
    {
        return [
            'id' => $solicitud->id,
            'estado' => $solicitud->estado,
            'estudiante' => [
                'identificacion' => $solicitud->student?->identificacion,
                'nombre' => trim(($solicitud->student?->nombre ?? '') . ' ' . ($solicitud->student?->apellidos ?? '')),
                'seccion' => $solicitud->student?->seccion,
            ],
            'urna' => $solicitud->urna ? [
                'id' => $solicitud->urna->id,
                'codigo' => $solicitud->urna->codigo,
            ] : null,
            'creada' => $solicitud->created_at?->toIso8601String(),
        ];
    }
}
