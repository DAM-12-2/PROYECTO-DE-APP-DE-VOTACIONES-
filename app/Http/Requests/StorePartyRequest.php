<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100|unique:parties,nombre',
            'siglas' => 'required|string|max:20|unique:parties,siglas',
            'fotopresidente' => 'nullable|string|max:255',
            'bandera' => 'nullable|string|max:255',
        ];
    }
}
