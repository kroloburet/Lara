<?php

namespace App\Http\Requests\XHR\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrCreateMaterialRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    protected string|null $categoriesTable = null;
    protected string|null $aliasRegex = null;

    protected function prepareForValidation(): void
    {
        $this->categoriesTable = config('app.materials.types.category.tableName');
        $this->aliasRegex = config('app.validation_rules.regex.alias');
    }

    public function authorize(): bool
    {
        return (
            isAdminCheck() &&
            $this->categoriesTable
        );
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // Attributes For All Materials
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
            'layout' => ['required', 'json'],
            'bg_image' => ['sometimes', 'nullable', 'string'], // Base64
            'category_id' => ['sometimes', 'nullable', "exists:{$this->categoriesTable},id"],
            'title' => ['required', 'string'],
            'description' => ['required', 'string', 'max:250'],
            'content' => ['nullable', 'string'],
            'robots' => ['string'],
            'css' => ['nullable', 'string'],
            'js' => ['nullable', 'string'],

            // Specific For All Static Materials
            'alias' => [ // Static materials may not have this field
                'sometimes',
                'required',
                "regex:{$this->aliasRegex}",
            ],

            // Specific For Contact Page
            'emails' => ['sometimes', 'nullable', 'json'],
            'phones' => ['sometimes', 'nullable', 'json'],
            'links' => ['sometimes', 'nullable', 'json'],
            'location' => ['sometimes', 'nullable', 'json'],
            'social_networks' => ['sometimes', 'nullable', 'json'],
        ];
    }
}
