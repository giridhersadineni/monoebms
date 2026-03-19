<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paperId = $this->route('paper')?->id;

        return [
            'code' => [
                'required', 'string', 'max:10',
                Rule::unique('subjects')
                    ->where('group_code', $this->input('group_code'))
                    ->where('medium',     $this->input('medium'))
                    ->where('semester',   $this->input('semester'))
                    ->where('scheme',     $this->input('scheme'))
                    ->ignore($paperId),
            ],
            'name'           => ['required', 'string', 'max:100'],
            'course'         => ['required', 'string', 'max:20', 'exists:courses,code'],
            'group_code'     => ['nullable', 'string', 'max:20'],
            'medium'         => ['required', 'in:TM,EM,BM'],
            'semester'       => ['required', 'integer', 'min:1', 'max:12'],
            'paper_type'     => ['required', 'in:compulsory,elective'],
            'elective_group' => ['nullable', 'string', 'max:5'],
            'part'           => ['required', 'integer', 'min:1', 'max:10'],
            'scheme'         => ['required', 'integer', 'min:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code'           => strtoupper(trim($this->input('code', ''))),
            'semester'       => (int) $this->input('semester'),
            'part'           => (int) $this->input('part', 1),
            'elective_group' => $this->input('elective_group') ?: null,
            'group_code'     => $this->input('group_code') ?: null,
        ]);
    }
}
