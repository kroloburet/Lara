<?php

namespace App\Http\Requests\XHR;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SetConsumerSettingsRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return auth($this->input('consumerType'))->check();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'consumerType' => [
                'required',
                'string',
                Rule::in(array_keys(config('app.consumers.types', []))),
            ],
            'dotTargetKey' => [
                'required',
                'string',
            ],
            'value' => [
                'required',
            ],
        ];
    }
}
