<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMesaRequest;
use App\Http\Requests\UpdateMesaRequest;
use App\Services\BitacoraService;
use Illuminate\Http\Request;

class MesaController extends Controller
{
    private BitacoraService $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
    }

    public function index()
    {
    }

    public function store(StoreMesaRequest $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(UpdateMesaRequest $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function storeSeccion(Request $request, $id)
    {
    }

    public function destroySeccion($id)
    {
    }

    public function moverSeccion(Request $request)
    {
    }
}
