<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Services\BitacoraService;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    private BitacoraService $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
    }

    public function index()
    {
    }

    public function store(StoreUsuarioRequest $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(UpdateUsuarioRequest $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function changePassword(Request $request)
    {
    }
}
