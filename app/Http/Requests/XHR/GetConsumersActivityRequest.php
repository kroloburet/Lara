<?php

namespace App\Http\Requests\XHR;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetConsumersActivityRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'consumers' => ['required', 'array', 'min:1'],
            'consumers.*.id' => ['required', 'integer', 'min:1'],
            'consumers.*.type' => [
                'required',
                'string',
                Rule::in(array_keys(config('app.consumers.types')))],
        ];
    }
}
