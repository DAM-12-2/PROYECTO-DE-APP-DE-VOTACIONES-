<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatoRequest;
use App\Services\BitacoraService;
use Illuminate\Http\Request;

class CandidatoController extends Controller
{
    private BitacoraService $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
    }

    public function index()
    {
    }

    public function storePuesto(Request $request)
    {
    }

    public function editPuesto($id)
    {
    }

    public function updatePuesto(Request $request, $id)
    {
    }

    public function destroyPuesto($id)
    {
    }

    public function storeCandidato(StoreCandidatoRequest $request)
    {
    }

    public function destroyCandidato($id)
    {
    }
}
