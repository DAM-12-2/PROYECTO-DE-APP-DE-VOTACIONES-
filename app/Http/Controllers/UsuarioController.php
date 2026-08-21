<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return view('admin.usuarios');
    }

    public function store(Request $request)
    {
        return back();
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $mesasList = Mesa::orderBy('numero')->get();

        return view('admin.usuarios_edit', compact('usuario', 'mesasList'));
    }

    public function update(Request $request, $id)
    {
        return back();
    }

    public function destroy($id)
    {
        return back();
    }

    public function changePassword(Request $request)
    {
        return back();
    }
}
