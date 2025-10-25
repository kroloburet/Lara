<?php

namespace App\Http\Requests\XHR\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetMaterialContentRequest extends FormRequest
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
            'alias' => [ // Static materials may not have this field
                'sometimes',
                'nullable',
                'string'
            ],
            'locale' => [
                'required',
                'string',
                Rule::in(array_values((array) config('app.available_locales'))),
            ],
            'type' => [
                'required',
                'string',
                Rule::in(array_keys((array) config('app.materials.types'))),
            ],
        ];
    }
}
