<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hall_ticket' => ['required', 'string', 'max:60'],
            'dob'         => ['required_without:dost_id', 'nullable', 'date_format:Y-m-d'],
            'dost_id'     => ['required_without:dob', 'nullable', 'string', 'max:50'],
        ];
    }
}
