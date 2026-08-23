<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUrnaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
return [
'codigo' => 'required|string|max:255|unique:urnas,codigo',
'id_mesa' => 'required|exists:mesas,id',
'horaactivacion' => 'nullable|string|max:255',
'estado' => 'nullable|integer|in:0,1,2',
];
    }
}
