<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrnaRequest;
use App\Http\Requests\UpdateUrnaRequest;
use App\Services\BitacoraService;
use App\Services\UrnaService;
use Illuminate\Http\Request;

class UrnaController extends Controller
{
    private BitacoraService $bitacoraService;
    private UrnaService $urnaService;

    public function __construct(BitacoraService $bitacoraService, UrnaService $urnaService)
    {
    }

    public function index()
    {
    }

    public function store(StoreUrnaRequest $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(UpdateUrnaRequest $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function activar(Request $request)
    {
    }

    public function desactivar(Request $request)
    {
    }
}
