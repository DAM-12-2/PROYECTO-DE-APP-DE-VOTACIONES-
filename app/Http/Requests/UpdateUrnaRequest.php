<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUrnaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => 'sometimes|required|string|max:10|unique:urnas,codigo,' . $this->route('id'),
            'horaactivacion' => 'nullable|string|max:20',
            'estado' => 'nullable|integer|in:0,1,2',
            'id_mesa' => 'nullable|exists:mesas,id',
        ];
    }
}
