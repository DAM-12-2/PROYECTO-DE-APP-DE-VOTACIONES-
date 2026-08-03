<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Vote;
use App\Models\Urna;
use App\Models\Party;
use App\Models\Mesa;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalEstudiantes = Student::count();
        $totalVotantes = Student::where('voto', true)->count();
        $urnasActivas = Urna::where('estado', 1)->count();
        $totalPartidos = Party::count();
        $totalMesas = Mesa::count();

        return view('admin.dashboard', compact('totalEstudiantes', 'totalVotantes', 'urnasActivas', 'totalPartidos', 'totalMesas'));
    }

    public function dashboardData()
    {
        $totalEstudiantes = Student::count();
        $totalVotantes = Student::where('voto', true)->count();
        $urnasActivas = Urna::where('estado', 1)->count();
        $totalPartidos = Party::count();
        $totalMesas = Mesa::count();

        return response()->json([
            'success' => true,
            'totalEstudiantes' => $totalEstudiantes,
            'totalVotantes' => $totalVotantes,
            'urnasActivas' => $urnasActivas,
            'totalPartidos' => $totalPartidos,
            'totalMesas' => $totalMesas,
        ]);
    }
}
