<?php

namespace App\Http\Controllers;

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
        return view('admin.usuarios_edit');
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
