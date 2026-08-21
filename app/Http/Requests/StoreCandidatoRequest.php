<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidatoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
<<<<<<< HEAD
            'nombre' => 'required|string|max:255',
            'partido_id' => 'required|exists:partidos,id',
            'puesto_id' => 'required|exists:puesto,id',
=======
            'student_id' => 'required|exists:students,id',
            'party_id' => 'required|exists:parties,id',
            'puesto_id' => 'required|exists:puestos,id',
            'foto' => 'nullable|string|max:255',
>>>>>>> 3e045c4 (Cambios en la base de datos, ya está al 100%)
        ];
    }
}
