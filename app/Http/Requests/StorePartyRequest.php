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
            'fotopresidente' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bandera' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
