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
        $query = Student::query();

        $busqueda = trim((string) $request->input('q'));
        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('identificacion', 'like', "%{$busqueda}%")
                    ->orWhere('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('apellidos', 'like', "%{$busqueda}%");
            });
        }

        $students = $query->orderBy('apellidos')->orderBy('nombre')->paginate(20)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $students->items(),
            ], 200);
        }

        return view('admin.students', compact('students', 'busqueda'));
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

    public function desbloquear(Request $request, $id, \App\Services\BitacoraService $bitacora)
    {
        $estudiante = Student::findOrFail($id);

        if (!$estudiante->voto) {
            return back()->with('error', 'Este estudiante no ha votado; no requiere desbloqueo.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($estudiante) {
            $estudiante->voto = false;
            $estudiante->save();
            \App\Models\Vote::where('student_id', $estudiante->id)->delete();
        });

        $bitacora->registrar('desbloqueo de estudiante', $estudiante->identificacion);

        return back()->with('success', "Se desbloqueó a {$estudiante->nombre} {$estudiante->apellidos} y se anuló su voto.");
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel|max:10240',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            return response()->json(['success' => false, 'message' => 'No se pudo leer el archivo.'], 422);
        }

        $insertados = 0;
        $rechazados = 0;
        $filas = 0;
        $limite = 10000;

        fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            $filas++;

            if ($filas > $limite || count($data) < 2) {
                $rechazados++;
                continue;
            }

            $identificacion = $this->limpiar($data[0] ?? '', 20, true);
            $nombre = $this->limpiar($data[1] ?? '', 80);
            $apellidos = $this->limpiar($data[2] ?? '', 80);
            $seccion = $this->limpiar($data[3] ?? '', 10);

            if ($identificacion === '' || $nombre === '') {
                $rechazados++;
                continue;
            }

            Student::updateOrCreate(
                ['identificacion' => $identificacion],
                [
                    'nombre' => $nombre,
                    'apellidos' => $apellidos,
                    'seccion' => $seccion !== '' ? $seccion : null,
                ]
            );

            $insertados++;
        }

        fclose($handle);

        return response()->json([
            'success' => true,
            'message' => "Se procesaron {$insertados} estudiantes. Filas rechazadas: {$rechazados}.",
            'insertados' => $insertados,
            'rechazados' => $rechazados,
        ], 200);
    }

    private function limpiar(string $valor, int $max, bool $soloDigitos = false): string
    {
        $valor = trim($valor);
        $valor = str_replace("\xEF\xBB\xBF", '', $valor);

        if (!mb_check_encoding($valor, 'UTF-8')) {
            $valor = mb_convert_encoding($valor, 'UTF-8', 'Windows-1252');
        }

        $valor = strip_tags($valor);
        $valor = preg_replace('/\s+/', ' ', $valor);

        if ($soloDigitos) {
            $valor = preg_replace('/\D/', '', $valor);
        }

        return mb_substr(trim($valor), 0, $max);
    }

    public function export()
    {
        $fileName = 'estudiantes_ctp_aira.csv';

        $rows = Student::query()
            ->orderBy('apellidos')
            ->orderBy('nombre')
            ->limit(20000)
            ->get()
            ->map(fn (Student $student) => [
                $student->identificacion,
                $student->nombre,
                $student->apellidos,
                $student->seccion,
                $student->voto ? 'Votó' : 'Pendiente',
            ]);

        return \App\Support\Csv::download(
            $fileName,
            ['Identificacion', 'Nombre', 'Apellidos', 'Seccion', 'Estado'],
            $rows
        );
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
