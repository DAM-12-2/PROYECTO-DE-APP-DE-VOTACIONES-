<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeeRequest;
use App\Models\TeeMember;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeeController extends Controller
{
    public function index()
    {
        $teeMembers = TeeMember::with('student')->get();
        $students = Student::where('estado', 1)->get();

        return view('admin.tee', compact('teeMembers', 'students'));
    }

    public function store(StoreTeeRequest $request)
    {
        TeeMember::create([
            'student_id' => $request->student_id,
            'puesto' => $request->puesto,
            'estado' => 1,
        ]);

        return redirect()->route('admin.tee')->with('success', 'Miembro del TEE registrado exitosamente.');
    }

    public function destroy($id)
    {
        $teeMember = TeeMember::findOrFail($id);
        $teeMember->delete();

        return redirect()->route('admin.tee')->with('success', 'Miembro del TEE eliminado exitosamente.');
    }
}
