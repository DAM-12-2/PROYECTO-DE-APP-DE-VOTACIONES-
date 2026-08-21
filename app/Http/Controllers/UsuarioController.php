<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios', compact('usuarios'));
    }

    public function store(StoreUsuarioRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'mesa_id' => $request->mesa_id,
            'must_change_password' => $request->role === 'jrv' ? 1 : 0,
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario registrado exitosamente.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios_edit', compact('usuario'));
    }

    public function update(UpdateUsuarioRequest $request, $id)
    {
        $usuario = User::findOrFail($id);

        $data = $request->only(['name', 'email', 'role', 'mesa_id']);
        $usuario->update($data);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();
        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'must_change_password' => 0,
        ]);

        return back()->with('success', 'Contraseña actualizada exitosamente.');
    }
}
