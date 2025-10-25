<?php

namespace App\Http\Requests\XHR;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaginateFilteredMaterialsRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                Rule::in(array_keys((array) config('app.materials.types'))),
            ],
            'locale' => [
                'sometimes',
                'string',
                Rule::in(array_values((array) config('app.available_locales'))),
            ],
            'category_id' => [
                'sometimes',
                'numeric',
                Rule::exists(config("app.materials.types.category.tableName"), 'id'),
            ],
            'query' => ['sometimes', 'nullable', 'string'],
            'order_by' => ['sometimes', 'string'],
            'offset' => ['sometimes', 'integer'],
            'limit' => ['sometimes', 'integer'],
        ];
    }
}
