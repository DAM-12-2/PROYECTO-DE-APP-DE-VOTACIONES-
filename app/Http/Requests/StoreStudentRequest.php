<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identificacion' => 'required|string|unique:students,identificacion|max:255',
            'nombre' => 'required|string|max:255',
            'mesa_id' => 'required|exists:mesas,id',
        ];
    }
}
