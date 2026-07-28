<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Urna;
use App\Services\StudentSearchService;
use App\Services\UrnaService;
use Illuminate\Http\Request;

class JrvController extends Controller
{
    private UrnaService $urnaService;
    private StudentSearchService $searchService;

    public function __construct(UrnaService $urnaService, StudentSearchService $searchService)
    {
        $this->urnaService = $urnaService;
        $this->searchService = $searchService;
    }

    public function index()
    {
    }

    public function searchStudents(Request $request)
    {
    }

    public function activarUrna(Request $request)
    {
    }

    public function desactivarUrna(Request $request)
    {
    }
}
