<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id;

        return [
            'code'             => ['required', 'string', 'max:20', 'uppercase',
                                   Rule::unique('courses', 'code')->ignore($courseId)],
            'name'             => ['required', 'string', 'max:100'],
            'total_semesters'  => ['required', 'integer', 'min:1', 'max:10'],
            'is_active'        => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'code'      => strtoupper($this->input('code', '')),
        ]);
    }
}
