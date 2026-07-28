<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncidenteRequest;
use App\Services\BitacoraService;

class IncidenteController extends Controller
{
    private BitacoraService $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
    }

    public function index()
    {
    }

    public function store(StoreIncidenteRequest $request)
    {
    }

    public function destroy($id)
    {
    }
}
