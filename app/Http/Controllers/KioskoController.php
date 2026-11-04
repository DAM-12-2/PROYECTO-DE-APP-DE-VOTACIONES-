<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Urna;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KioskoController extends Controller
{
    public function show()
    {
        return view('voting.urna', [
            'tiempoVotacion' => $this->tiempoVotacion(),
            'urna' => $this->urnaActual(),
        ]);
    }

    public function activar(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:10',
            'usuario' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        if (\Illuminate\Support\Facades\DB::connection()->getDriverName() === 'pgsql') {
            $publica = \Illuminate\Support\Facades\DB::selectOne(
                'SELECT * FROM urna_por_codigo(?)',
                [$data['codigo']]
            );
        } else {
            $publica = Urna::where('codigo', $data['codigo'])->first();
        }

        if (!$publica) {
            return back()->withErrors(['codigo' => 'El código de urna no existe.'])->withInput();
        }

        $usuario = User::where('name', $data['usuario'])->where('role', 'jrv')->first();

        if (!$usuario || !$usuario->verificarPassword($data['password'])) {
            return back()->withErrors(['usuario' => 'Usuario o contraseña de JRV inválidos.'])->withInput();
        }

        if ($usuario->must_change_password) {
            return back()->withErrors(['usuario' => 'Debe cambiar su contraseña antes de operar la urna.'])->withInput();
        }

        if ((int) $usuario->mesa_id !== (int) $publica->id_mesa) {
            return back()->withErrors(['usuario' => 'Este usuario JRV no pertenece a la mesa de la urna.'])->withInput();
        }

        if (Setting::where('nombre', 'eleccion_abierta')->value('detalle') !== '1') {
            return back()->withErrors(['codigo' => 'La elección está cerrada. El Tribunal debe abrirla primero.'])->withInput();
        }

        Auth::login($usuario);
        $request->session()->regenerate();

        $this->setRlsContext($usuario);

        Urna::where('id', $publica->id)->update([
            'estado' => 1,
            'horaactivacion' => now()->format('H:i:s'),
        ]);

        $request->session()->put('urna_id', $publica->id);

        return redirect('/kiosko')->with('success', "Urna {$publica->codigo} activada.");
    }

    public function cerrar(Request $request)
    {
        $urna = $this->urnaActual();

        if ($urna) {
            $urna->update(['estado' => 0, 'id_estudiante' => null]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/kiosko');
    }

    public function estado()
    {
        $usuario = Auth::user();
        $urna = $this->urnaActual();

        if (!$usuario || !$usuario->isJrv() || !$urna) {
            return response()->json(['autenticado' => false]);
        }

        $urna = $urna->fresh();
        $estudiante = $urna->estudiante;

        $solicitud = \App\Models\VoteRequest::where('urna_id', $urna->id)
            ->whereIn('estado', [\App\Models\VoteRequest::APROBADA, \App\Models\VoteRequest::VOTANDO])
            ->first();

        return response()->json([
            'autenticado' => true,
            'urna' => [
                'id' => $urna->id,
                'codigo' => $urna->codigo,
                'mesa' => $urna->mesa?->nombre,
                'estado' => $urna->estado,
            ],
            'estudiante' => $estudiante ? [
                'identificacion' => $estudiante->identificacion,
                'nombre' => trim($estudiante->nombre . ' ' . $estudiante->apellidos),
                'seccion' => $estudiante->seccion,
            ] : null,
            'solicitud' => $solicitud ? [
                'id' => $solicitud->id,
                'estado' => $solicitud->estado,
            ] : null,
            'eleccion_abierta' => Setting::where('nombre', 'eleccion_abierta')->value('detalle') === '1',
            'tiempo_votacion' => $this->tiempoVotacion(),
        ]);
    }


    private function setRlsContext(User $usuario): void
    {
        if (\Illuminate\Support\Facades\DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        \Illuminate\Support\Facades\DB::selectOne("SELECT set_config('app.role', ?, false)", ['jrv']);
        \Illuminate\Support\Facades\DB::selectOne("SELECT set_config('app.user_id', ?, false)", [(string) $usuario->id]);
        \Illuminate\Support\Facades\DB::selectOne("SELECT set_config('app.mesa_id', ?, false)", [(string) $usuario->mesa_id]);
    }

    private function urnaActual(): ?Urna
    {
        $id = session('urna_id');

        return $id ? Urna::find($id) : null;
    }

    private function tiempoVotacion(): int
    {
        return (int) (Setting::where('nombre', 'tiempo_votacion')->value('detalle') ?? 90);
    }
}
