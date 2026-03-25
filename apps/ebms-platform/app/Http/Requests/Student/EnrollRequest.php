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
            'exam_id'                => ['required', 'integer', 'exists:exams,id'],
            'compulsory_subjects'    => ['nullable', 'array'],
            'compulsory_subjects.*'  => ['integer', 'exists:subjects,id'],
            'elective_subjects'      => ['nullable', 'array'],
            'elective_subjects.*'    => ['integer', 'exists:subjects,id'],
            'improvement_subjects'   => ['nullable', 'array'],
            'improvement_subjects.*' => ['integer', 'exists:subjects,id'],
        ];
    }
}
