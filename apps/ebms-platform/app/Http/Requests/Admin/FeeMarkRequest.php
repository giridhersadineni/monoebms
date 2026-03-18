<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FeeMarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'challan_number'       => ['required', 'string', 'max:50'],
            'challan_submitted_on' => ['required', 'date'],
            'challan_received_by'  => ['required', 'string', 'max:50'],
        ];
    }
}
