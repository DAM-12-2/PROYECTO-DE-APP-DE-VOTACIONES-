<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route('estudiante') ?? $this->route('id');
        return [
            'identificacion' => 'sometimes|required|string|unique:students,identificacion,' . $studentId,
            'nombre' => 'sometimes|required|string|max:255',
            'apellidos' => 'sometimes|required|string|max:255',
            'seccion' => 'sometimes|nullable|string|max:20',
        ];
    }
}
