<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMainGuaranteeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'embassy' => 'required|string|max:255',
            'embassy_ref' => 'required|string|max:255',
            'number' => 'nullable|string|max:255',
            'mb' => 'nullable|string|max:255',
            'issue_date' => 'required|date',
            'cover_start_date' => 'required|date',
            'cover_end_date' => 'required|date',
            'guarantee_cases' => 'required|array|min:1',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }
}
