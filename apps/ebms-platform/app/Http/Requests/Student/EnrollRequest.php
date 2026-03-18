<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class EnrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exam_id'            => ['required', 'integer', 'exists:exams,id'],
            'compulsory_subjects' => ['required', 'array', 'min:1'],
            'compulsory_subjects.*' => ['integer', 'exists:subjects,id'],
            'elective_subjects'  => ['nullable', 'array'],
            'elective_subjects.*' => ['integer', 'exists:subjects,id'],
        ];
    }
}
