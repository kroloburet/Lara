<?php

namespace App\Http\Requests\XHR\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MediaSelectorRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    protected function prepareForValidation(): void
    {
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
        return auth()->check();
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'media' => ['required', 'array'],
            'media.path' => ['required', 'string'],
            'media.limit' => ['required', 'integer', 'min:1'],
            'media.files' => ['nullable', 'array'],
            'media.files.*' => [
                'file',
                'mimes:jpg,jpeg,png,gif,svg,webp,mp4,mov,ogg,pdf',
                'max:256000', // 250 MB
            ],
            'media.order' => ['required', 'json'],
            'media.order_items' => [
                'nullable',
                'array',
                // Validate that the number of items does not exceed the provided limit
                Rule::prohibitedIf(function () {
                    return count($this->input('media.order_items', [])) > $this->input('media.limit', 0);
                }),
            ],
            'media.order_items.*.id' => ['required', 'string'],
            'media.order_items.*.name' => ['required', 'string', 'max:255'],
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
