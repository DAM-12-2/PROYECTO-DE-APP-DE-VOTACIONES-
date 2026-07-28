<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartyRequest;
use App\Http\Requests\UpdatePartyRequest;
use App\Services\BitacoraService;
use App\Services\FileUploadService;

class PartyController extends Controller
{
    private BitacoraService $bitacoraService;
    private FileUploadService $fileUploadService;

    public function __construct(BitacoraService $bitacoraService, FileUploadService $fileUploadService)
    {
    }

    public function index()
    {
    }

    public function store(StorePartyRequest $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(UpdatePartyRequest $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
