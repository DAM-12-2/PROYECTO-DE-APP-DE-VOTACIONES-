<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeeRequest extends FormRequest
{
    public function authorize(): bool
    {
    }

    public function rules(): array
    {
    }
}
