<?php

namespace App\Http\Requests\XHR;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class PaginateFilteredModeratorsRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return Gate::allows('permits', ['moderator', 'r']);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'query' => ['sometimes', 'nullable', 'string'],
            'order_by' => ['sometimes', 'string'],
            'offset' => ['sometimes', 'integer'],
            'limit' => ['sometimes', 'integer'],
        ];
    }
}
