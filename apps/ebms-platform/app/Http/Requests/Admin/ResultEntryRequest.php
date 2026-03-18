<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ResultEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'results'                         => ['required', 'array'],
            'results.*'                       => ['array'],
            'results.*.*'                     => ['array'],
            'results.*.*.ext'                 => ['required', 'string'],
            'results.*.*.int'                 => ['required', 'string'],
        ];
    }
}
