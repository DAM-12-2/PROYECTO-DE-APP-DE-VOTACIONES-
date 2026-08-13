<?php

namespace App\Services;

use App\Models\Student;

class StudentSearchService
{
    public function buscar(string $identificacion): ?Student
    {
        return Student::where('identificacion', $identificacion)->first();
    }
}
