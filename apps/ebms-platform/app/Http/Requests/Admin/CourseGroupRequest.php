<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseGroupRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $courseId = $this->route('course')->id;
        $groupId  = $this->route('group')?->id;

        return [
            'code'      => ['required', 'string', 'max:20',
                            Rule::unique('course_groups', 'code')
                                ->where('course_id', $courseId)
                                ->ignore($groupId)],
            'name'      => ['required', 'string', 'max:100'],
            'mediums'   => ['nullable', 'array'],
            'mediums.*' => ['string', 'in:TM,EM,BM'],
            'schemes'   => ['nullable', 'array'],
            'schemes.*' => ['string', 'max:10'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Convert comma-separated schemes string to array
        $schemes = $this->input('schemes');
        $schemesArray = $schemes
            ? array_filter(array_map('trim', explode(',', $schemes)))
            : null;

        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'code'      => strtoupper($this->input('code', '')),
            'schemes'   => $schemesArray ?: null,
        ]);
    }
}
