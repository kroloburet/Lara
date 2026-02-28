<?php

namespace App\Http\Requests\XHR\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SetAppLayoutRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return isAdminCheck();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'settings' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (! is_array($value) && ! is_string($value)) {
                        $fail("The {$attribute} must be a string or an array.");
                    }
                },
            ],
            'material_type' => ['required', 'string'],
            'opt' => ['required', 'string'],
        ];
    }
}
