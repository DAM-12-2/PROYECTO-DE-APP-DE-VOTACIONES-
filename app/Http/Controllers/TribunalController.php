<?php

namespace App\Http\Controllers;

class TribunalController extends Controller
{
    public function index()
    {
        return view('tribunal.index');
    }

    public function estudiantes()
    {
        return view('tribunal.estudiantes');
    }

    public function configuracion()
    {
        return view('tribunal.configuracion');
    }
}
