<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePartyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $partyId = $this->route('partido') ?? $this->route('id');
        return [
            'siglas' => 'sometimes|required|string|max:50|unique:parties,siglas,' . $partyId,
            'nombre' => 'sometimes|required|string|max:100|unique:parties,nombre,' . $partyId,
            'estado' => 'sometimes|boolean',
        ];
    }
}
