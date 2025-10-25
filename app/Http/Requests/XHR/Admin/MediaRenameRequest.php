<?php

namespace App\Http\Requests\XHR\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MediaRenameRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

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
            "media.path" => ["required", "string"],
            "media.id" => ["required", "string"],
            "media.old_name" => ["required", "string", "max:255"],
            "media.new_name" => [
                "required",
                "string",
                "max:255",
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
}
