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

        $urna = Urna::where('codigo', $data['codigo'])->first();

        if (!$urna) {
            return back()->withErrors(['codigo' => 'El código de urna no existe.'])->withInput();
        }

        $usuario = User::where('name', $data['usuario'])->where('role', 'jrv')->first();

        if (!$usuario || !Hash::check($data['password'], $usuario->password)) {
            return back()->withErrors(['usuario' => 'Usuario o contraseña de JRV inválidos.'])->withInput();
        }

        if ((int) $usuario->mesa_id !== (int) $urna->id_mesa) {
            return back()->withErrors(['usuario' => 'Este usuario JRV no pertenece a la mesa de la urna.'])->withInput();
        }

        Auth::login($usuario);
        $request->session()->regenerate();
        $request->session()->put('urna_id', $urna->id);

        $urna->update([
            'estado' => 1,
            'horaactivacion' => now()->format('H:i:s'),
        ]);

        return redirect('/kiosko')->with('success', "Urna {$urna->codigo} activada.");
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
            'eleccion_abierta' => Setting::where('nombre', 'eleccion_abierta')->value('detalle') === '1',
            'tiempo_votacion' => $this->tiempoVotacion(),
        ]);
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
