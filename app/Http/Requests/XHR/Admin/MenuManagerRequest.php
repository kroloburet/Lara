<?php

namespace App\Http\Requests\XHR\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuManagerRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return isAdminCheck();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'menu' => ['required', 'array'],
            'menu.locale' => [
                'required',
                'string',
                Rule::in(config('app.available_locales', [])),
            ],
            'menu.item_id' => [
                'sometimes',
                'nullable',
                'exists:menu,id',
            ],
            'menu.parent_id' => [
                'sometimes',
                'nullable',
                'exists:menu,id',
            ],
            'menu.title' => [
                'sometimes',
                'required',
                'string',
            ],
            'menu.url' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'menu.order_position' => [
                'sometimes',
                'required',
            ],
            'menu.order' => [
                'sometimes',
            ],
            'menu.target' => [
                'sometimes',
                'required',
            ],
        ];
    }
}
