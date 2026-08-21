<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Events\StudentUpdated;

class StudentController extends Controller
{
    // Listar todos los estudiantes (Respuesta JSON)
    public function index(Request $request)
    {
        $students = Student::all();
        return response()->json([
            'success' => true,
            'data' => $students
        ], 200);
    }

    // Crear un estudiante individual
    public function store(Request $request)
    {
        $validated = $request->validate([
            'identificacion' => 'required|unique:students,identificacion',
            'nombre'         => 'required|string',
            'correo'         => 'nullable|email',
            'seccion'        => 'nullable|string'
        ]);
        broadcast(new StudentUpdated($student));

        $student = Student::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Estudiante creado exitosamente',
            'data'    => $student
        ], 201);
    }

    // Actualizar un estudiante
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
        }

        $student->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Estudiante actualizado exitosamente',
            'data'    => $student
        ], 200);
        broadcast(new StudentUpdated($student));
    }

    // Eliminar un estudiante
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Estudiante no encontrado'], 404);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Estudiante eliminado exitosamente'
        ], 200);
    }

    // 📥 IMPORTAR ESTUDIANTES DESDE CSV
    public function import(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $path = $request->file('archivo')->getRealPath();
        $file = fopen($path, 'r');

        fgetcsv($file); // Omitir el encabezado del CSV

        $insertados = 0;

        while (($datos = fgetcsv($file, 1000, ",")) !== FALSE) {
            if (!empty($datos[0])) {
                Student::updateOrCreate(
                    ['identificacion' => $datos[0]],
                    [
                        'nombre'  => $datos[1] ?? '',
                        'correo'  => $datos[2] ?? null,
                        'seccion' => $datos[3] ?? null,
                    ]
                );
                $insertados++;
            }
        }

        fclose($file);

        return response()->json([
            'success' => true,
            'message' => "Se procesaron {$insertados} estudiantes correctamente."
        ], 200);
    }

    // 📤 EXPORTAR ESTUDIANTES A CSV
    public function export()
    {
        $fileName = 'estudiantes_ctp_aira.csv';
        $estudiantes = Student::all();

        header("Content-type: text/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Pragma: no-cache");
        header("Expires: 0");

        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para UTF-8 en Excel
        fputcsv($file, ['Identificacion', 'Nombre', 'Correo', 'Seccion']);

        foreach ($estudiantes as $e) {
            fputcsv($file, [
                $e->identificacion,
                $e->nombre,
                $e->correo,
                $e->seccion
            ]);
        }

        fclose($file);
        exit;
    }

    // Buscar estudiante por identificación
    public function search(Request $request)
    {
        $identificacion = $request->input('identificacion');

        $estudiante = Student::where('identificacion', $identificacion)->first();

        if ($estudiante) {
            return response()->json([
                'success' => true,
                'data'    => $estudiante
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }
}