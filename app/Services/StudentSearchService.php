<?php

namespace App\Services;

use App\Models\Student;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class StudentSearchService
{
    public function buscar(string $identificacion): ?Student
    {
        return Student::where('identificacion', $identificacion)->first();
    }

    public function search(string $query, ?int $mesaId = null, bool $onlyAvailable = true): Collection
    {
        try {
            $consulta = Student::where(function ($q) use ($query) {
                $q->where('identificacion', 'like', "%{$query}%")
                    ->orWhere('nombre', 'like', "%{$query}%");
            });

<<<<<<< HEAD
            if ($mesaId !== null) {
                $consulta->where('mesa_id', $mesaId);
            }

=======
>>>>>>> 3e045c4 (Cambios en la base de datos, ya está al 100%)
            if ($onlyAvailable) {
                $consulta->where('estado', 1);
            }

            return $consulta->get();
        } catch (Exception $e) {
            Log::error("Error en StudentSearchService@search: " . $e->getMessage());
            return new Collection();
        }
    }

    public function getAvailableStudents(): Collection
    {
        try {
            return Student::where('estado', 1)->get();
        } catch (Exception $e) {
            Log::error("Error en StudentSearchService@getAvailableStudents: " . $e->getMessage());
            return new Collection();
        }
    }
}
