<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Vote;
use App\Models\Urna;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalEstudiantes = Student::count();
        $totalVotantes = Student::where('voto', true)->count();
        $urnasActivas = Vote::where('estado', 1)->count();

        return response()->json([
            'success' => true,
            'totalEstudiantes' => $totalEstudiantes,
            'totalVotantes' => $totalVotantes,
            'urnasActivas' => $urnasActivas,
        ]);
    }

    public function dashboardData()
    {
        return response()->json([]);
    }
}
