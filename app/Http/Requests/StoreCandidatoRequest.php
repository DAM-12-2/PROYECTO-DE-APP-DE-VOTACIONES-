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
            'nombre' => 'required|string|max:255',
            'partido_id' => 'required|exists:partidos,id',
            'puesto_id' => 'required|exists:puesto,id',
        ];
    }
}
