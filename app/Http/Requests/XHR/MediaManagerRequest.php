<?php

namespace App\Http\Requests\XHR;

use App\Models\Abstract\Material;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaManagerRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    public Material|null $material = null;

    protected function prepareForValidation(): void
    {
        $this->material = materialBuilder($this->input('material_type'))
            ->find($this->input('material_id'));

        // Decode the JSON string from 'media.order' into a new top-level array
        // to make its contents directly available for validation.
        if ($this->input('media.order')) {
            $this->merge([
                'media.order_items' => json_decode($this->input('media.order'), true) ?? []
            ]);
        }
    }

    public function authorize(): bool
    {
        return (
            auth()->check() &&
            !empty($this->material)
        );
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'material_type' => [
                'required',
                Rule::in(array_keys((array) config('app.materials.types'))),
            ],
            'material_id' => [
                'required',
                'integer',
            ],
            'media' => ['required', 'array'],
            'media.path' => ['required', 'string'],
            'media.limit' => ['sometimes', 'required', 'integer', 'min:1'],
            'media.files' => ['sometimes', 'nullable', 'array'],
            'media.files.*' => [
                'file',
                'mimes:jpg,jpeg,png,gif,svg,webp,mp4,mov,ogg,pdf',
                'max:256000', // 250 MB
            ],
            'media.order' => ['sometimes', 'required', 'json'],
            'media.order_items' => [
                'sometimes',
                'nullable',
                'array',
                // Validate that the number of items does not exceed the provided limit
                Rule::prohibitedIf(function () {
                    return count($this->input('media.order_items', [])) > $this->input('media.limit', 0);
                }),
            ],
            'media.order_items.*.id' => ['required', 'string'],
            'media.order_items.*.name' => ['required', 'string', 'max:255'],

            // Rename action
            'media.id' => ['sometimes', 'required', 'string'],
            'media.old_name' => ['sometimes', 'required', 'string', 'max:255'],
            'media.new_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                // This rule forbids characters that are invalid in filenames on most operating systems.
                // It prevents directory traversal attacks.
                'not_regex:/[\\\\\/:\*\?"<>|]/',
                // Custom rule to check byte length for filesystem compatibility
                function ($attribute, $value, $fail) {
                    if (strlen($value) > 255) { // strlen() counts bytes, not characters
                        $fail(__('validation.custom.media.name.max_bytes'));
                    }
                },
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'media.order_items.prohibited' => __('component.media_selector.limit_alert', ['limit' => $this->input('media.limit', 0)]),
        ];
    }
}
