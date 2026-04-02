<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Personal
            'name'             => ['required', 'string', 'max:60'],
            'father_name'      => ['nullable', 'string', 'max:60'],
            'mother_name'      => ['nullable', 'string', 'max:60'],
            'gender'           => ['nullable', 'in:M,F,O'],
            'dob'              => ['nullable', 'date'],
            'aadhaar'          => ['nullable', 'string', 'max:12'],
            'caste'            => ['nullable', 'string', 'max:30'],
            'sub_caste'        => ['nullable', 'string', 'max:30'],
            'email'            => ['nullable', 'email', 'max:50'],
            'phone'            => ['nullable', 'string', 'max:30'],

            // Academic
            'course'           => ['required', 'string', 'max:20', 'exists:courses,code'],
            'course_name'      => ['nullable', 'string', 'max:100'],
            'group_code'       => ['nullable', 'string', 'max:20'],
            'medium'           => ['required', 'in:TM,EM,BM'],
            'scheme'           => ['required', 'integer', 'min:2000'],
            'semester'         => ['required', 'integer', 'min:1', 'max:12'],
            'admission_year'   => ['nullable', 'integer', 'min:2000', 'max:2100'],

            // Address
            'address'          => ['nullable', 'string', 'max:60'],
            'address2'         => ['nullable', 'string', 'max:60'],
            'mandal'           => ['nullable', 'string', 'max:40'],
            'city'             => ['nullable', 'string', 'max:30'],
            'state'            => ['nullable', 'string', 'max:20'],
            'pincode'          => ['nullable', 'string', 'max:10'],

            // Other
            'dost_id'          => ['nullable', 'string', 'max:30'],
            'apaar_id'         => ['nullable', 'string', 'max:30'],
            'ssc_hall_ticket'  => ['nullable', 'string', 'max:30'],
            'inter_hall_ticket'=> ['nullable', 'string', 'max:30'],
            'challenged_quota' => ['nullable', 'string', 'max:20'],
            'is_active'        => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'group_code' => $this->input('group_code') ?: null,
        ]);
    }
}
