<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdditionalGuaranteeRequest extends FormRequest
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
            'cover_end_date' => 'nullable|date|after_or_equal:cover_start_date',
            'total_price' => 'nullable|string', // Allow string to handle commas before str_replace
            'details' => 'required|array|min:1',
            'details.*.additional_case' => 'nullable|string',
            'details.*.specific_dates' => 'nullable|array',
            'details.*.specific_dates.*' => 'nullable|date',
            'details.*.date_range_start' => 'nullable|date',
            'details.*.date_range_end' => 'nullable|date|after_or_equal:details.*.date_range_start',
            'details.*.detail' => 'required|string',
            'details.*.definition' => 'nullable|string',
            'details.*.amount' => 'nullable|string',
            'details.*.price' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }
}
