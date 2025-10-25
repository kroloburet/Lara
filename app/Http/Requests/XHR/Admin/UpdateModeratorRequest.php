<?php

namespace App\Http\Requests\XHR\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateModeratorRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    protected string|null $emailRegex = null;

    protected function prepareForValidation(): void
    {
        $this->emailRegex = config('app.validation_rules.regex.email');
    }

    public function authorize(): bool
    {
        return Gate::allows('permits', ['moderator', 'u']);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric', 'exists:admins'],
            'password' => ['nullable', Password::default()->uncompromised(), 'confirmed'],
            'email' => [
                'required',
                'email',
                "regex:{$this->emailRegex}",
                Rule::unique('admins')->ignore($this->id),
            ],
            'permissions' => ['required', 'json'],
        ];
    }
}
