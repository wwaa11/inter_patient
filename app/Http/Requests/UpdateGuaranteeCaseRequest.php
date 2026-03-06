<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuaranteeCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'case' => 'required|string|max:255',
            'definition' => 'nullable|string|max:255',
            'colour' => 'nullable|string|max:255',
        ];
    }
}
