<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hn = $this->route('hn');

        return [
            'hn' => 'required|string|max:20|unique:patients,hn,'.$hn.',hn',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female,Other',
            'birthday' => 'required|date',
            'qid' => 'required|string|max:20',
            'nationality' => 'nullable|string|max:100',
            'sex' => 'nullable|string|max:10',
            'type' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
        ];
    }
}
