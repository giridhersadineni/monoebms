<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:200'],
            'course'           => ['required', 'string', 'max:20'],
            'exam_type'        => ['required', 'string', 'in:regular,supplementary,special,backlog'],
            'semester'         => ['required', 'integer', 'min:1', 'max:10'],
            'month'            => ['required', 'integer', 'min:1', 'max:12'],
            'year'             => ['required', 'integer', 'min:2000', 'max:2100'],
            'status'           => ['required', 'in:NOTIFY,RUNNING,REVALOPEN,CLOSED'],
            'scheme'           => ['nullable', 'string', 'max:50'],
            'revaluation_open' => ['boolean'],
            'fee_regular'      => ['required', 'integer', 'min:0'],
            'fee_supply_upto2' => ['required_if:exam_type,supplementary', 'nullable', 'integer', 'min:0'],
            'fee_improvement'  => ['nullable', 'integer', 'min:0'],
            'fee_fine'         => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'revaluation_open' => $this->boolean('revaluation_open'),
        ]);
    }
}
