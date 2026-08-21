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
            'student_id' => 'required|exists:students,id',
            'party_id' => 'required|exists:parties,id',
            'puesto_id' => 'required|exists:puestos,id',
            'foto' => 'nullable|string|max:255',
        ];
    }
}
