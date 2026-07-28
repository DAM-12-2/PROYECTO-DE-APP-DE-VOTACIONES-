<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class StudentsImport implements ToCollection, WithHeadingRow, WithValidation, WithLimit, SkipsOnFailure
{
    use SkipsFailures;

    public int $inserted = 0;
    public int $updated = 0;
    public int $errors = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $identificacion = trim($row['identificacion'] ?? '');
            $nombre = trim($row['nombre'] ?? '');
            $apellidos = trim($row['apellidos'] ?? '');
            $seccion = trim($row['seccion'] ?? '');

            if (empty($identificacion) || empty($nombre)) {
                $this->errors++;
                continue;
            }

            try {
                $existing = Student::where('identificacion', $identificacion)->first();

                if ($existing) {
                    $existing->update([
                        'nombre' => $nombre,
                        'apellidos' => $apellidos,
                        'seccion' => $seccion,
                    ]);
                    $this->updated++;
                } else {
                    Student::create([
                        'identificacion' => $identificacion,
                        'nombre' => $nombre,
                        'apellidos' => $apellidos,
                        'seccion' => $seccion,
                        'voto' => 0,
                        'estado' => 1,
                    ]);
                    $this->inserted++;
                }
            } catch (\Exception $e) {
                $this->errors++;
            }
        }
    }

    public function rules(): array
    {
        return [
            'identificacion' => 'required|string|max:20',
            'nombre' => 'required|string|max:50',
            'apellidos' => 'nullable|string|max:50',
            'seccion' => 'nullable|string|max:10',
        ];
    }

    public function limit(): int
    {
        return 2000;
    }
}
