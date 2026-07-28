<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeeRequest;
use App\Services\BitacoraService;

class TeeController extends Controller
{
    private BitacoraService $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
    }

    public function index()
    {
    }

    public function store(StoreTeeRequest $request)
    {
    }

    public function destroy($id)
    {
    }
}
