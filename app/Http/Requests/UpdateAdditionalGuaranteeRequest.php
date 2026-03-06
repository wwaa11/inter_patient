<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdditionalGuaranteeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|max:255',
            'embassy_ref' => 'nullable|string|max:255',
            'mb' => 'nullable|string|max:255',
            'issue_date' => 'required|date',
            'cover_start_date' => 'nullable|date',
            'cover_end_date' => 'nullable|date',
            'total_price' => 'nullable|string',
            'additional_case' => 'nullable|string',
            'specific_dates' => 'nullable|array',
            'specific_dates.*' => 'nullable|date',
            'date_range_start' => 'nullable|date',
            'date_range_end' => 'nullable|date',
            'detail' => 'required|string',
            'definition' => 'nullable|string',
            'amount' => 'nullable|string',
            'price' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'files_to_remove' => 'nullable|string',
        ];
    }
}
