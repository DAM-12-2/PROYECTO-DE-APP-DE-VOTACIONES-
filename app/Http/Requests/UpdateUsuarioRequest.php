<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255|unique:users,name,' . $this->route('id'),
            'role' => 'sometimes|required|in:admin,tee,jrv',
            'mesa_id' => 'nullable|exists:mesas,id',
        ];
    }
}
