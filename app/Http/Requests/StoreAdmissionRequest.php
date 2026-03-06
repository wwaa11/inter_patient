<?php

namespace App\Http\Requests;

use App\Models\Admission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdmissionRequest extends FormRequest
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
            'hn' => ['required', 'string', 'max:50'],
            'name' => ['nullable', 'string', 'max:255'],
            'admission_date' => ['nullable', 'date'],
            'room_no' => ['nullable', 'string', 'max:50'],
            'diagnosis' => ['nullable', 'string'],
            'procedure_treatment' => ['nullable', 'string'],
            'contact_providers' => ['nullable', 'array'],
            'contact_providers.*' => ['integer', 'exists:providers,id'],
            'pre_authorization_id' => ['nullable', 'exists:pre_authorizations,id'],
            'additional_note' => ['nullable', 'string'],
            'department' => ['nullable', Rule::in(Admission::departmentOptions())],
            'admitting_status' => ['nullable', Rule::in(Admission::admittingStatusOptions())],
            'case_status' => ['nullable', Rule::in(Admission::caseStatusOptions())],
            'sent_out_date' => ['nullable', 'date'],
            'handling_users' => ['nullable', 'array'],
            'handling_users.*' => ['integer', 'exists:users,id'],
            'initial_gop_receiving_date' => ['nullable', 'date'],
            'gop_pre_certification_status' => ['nullable', Rule::in(Admission::gopPreCertificationStatusOptions())],
            'gop_ref' => ['nullable', 'string', 'max:255'],
            'gop_translators' => ['nullable', 'array'],
            'gop_translators.*' => ['integer', 'exists:users,id'],
            'discharge_date' => ['nullable', 'date'],
            'final_gop' => ['nullable', 'date'],
            'gop_attachments' => ['nullable', 'array', 'min:0'],
            'gop_attachments.*' => ['file', 'mimes:pdf,jpeg,jpg,png,gif', 'max:10240'],
        ];
    }
}
