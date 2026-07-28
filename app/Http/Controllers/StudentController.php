<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Services\BitacoraService;
use App\Services\ImportService;
use App\Services\StudentSearchService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private BitacoraService $bitacoraService;
    private ImportService $importService;
    private StudentSearchService $searchService;

    public function __construct(BitacoraService $bitacoraService, ImportService $importService, StudentSearchService $searchService)
    {
    }

    public function index(Request $request)
    {
    }

    public function store(StoreStudentRequest $request)
    {
    }

    public function edit($id)
    {
    }

    public function update(UpdateStudentRequest $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function import(Request $request)
    {
    }

    public function search(Request $request)
    {
    }
}
