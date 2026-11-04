<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Events\StudentUpdated;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::all();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $students
            ], 200);
        }

        return view('admin.students', compact('students'));
    }

    public function store(StoreStudentRequest $request)
    {
        $student = Student::create($request->validated());
        broadcast(new StudentUpdated($student));

        return back()->with('success', 'Estudiante registrado exitosamente.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('admin.students_edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update($request->validated());
        broadcast(new StudentUpdated($student));

        return back()->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return back()->with('success', 'Estudiante eliminado exitosamente.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $insertados = 0;

        // Skip header
        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) < 2) continue;

            Student::updateOrCreate(
                ['identificacion' => $data[0]],
                [
                    'nombre' => $data[1] ?? '',
                    'apellidos' => $data[2] ?? '',
                    'seccion' => $data[3] ?? null,
                ]
            );
            $insertados++;
        }

        fclose($handle);

        return response()->json([
            'success' => true,
            'message' => "Se procesaron {$insertados} estudiantes correctamente."
        ], 200);
    }

    public function export()
    {
        $students = Student::all();
        $fileName = 'estudiantes_ctp_aira.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Identificacion', 'Nombre', 'Apellidos', 'Seccion', 'Estado']);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->identificacion,
                    $student->nombre,
                    $student->apellidos,
                    $student->seccion,
                    $student->estado,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function search(Request $request)
    {
        $identificacion = $request->input('identificacion');

        $student = Student::where('identificacion', $identificacion)->first();

        if ($student) {
            return response()->json([
                'success' => true,
                'data' => $student
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }
}
