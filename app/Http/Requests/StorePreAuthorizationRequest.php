<?php

namespace App\Http\Requests;

use App\Models\PreAuthorization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePreAuthorizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_type_id' => ['required', 'exists:service_types,id'],
            'provider_id' => ['required', 'exists:providers,id'],
            'hn' => ['required', 'string', 'max:50'],
            'patient_name' => ['nullable', 'string', 'max:255'],
            'date_of_service' => ['nullable', 'date'],
            'operations_procedures' => ['nullable', 'string'],
            'notifier_id' => ['nullable', 'exists:notifiers,id'],
            'requested_date' => ['nullable', 'date'],
            'handling_staffs' => ['nullable', 'array'],
            'handling_staffs.*' => ['integer', 'exists:users,id'],
            'case_status' => ['required', Rule::in(PreAuthorization::caseStatusOptions())],
            'coverage_decision' => ['nullable', Rule::in(PreAuthorization::coverageDecisionOptions())],
            'send_out_date' => ['nullable', 'date'],
            'gop_receiving_date' => ['nullable', 'date'],
            'gop_reference_number' => ['nullable', 'string', 'max:255'],
            'gop_translate_by' => ['nullable', 'exists:users,id'],
            'gop_attachments' => ['nullable', 'array'],
            'gop_attachments.*' => ['file', 'mimes:pdf,jpeg,jpg,png,gif', 'max:10240'],
        ];
    }
}
