<?php

namespace App\Http\Requests\XHR\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class CreateModeratorRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    protected string|null $emailRegex = null;

    protected function prepareForValidation(): void
    {
        $this->emailRegex = config('app.validation_rules.regex.email');
    }

    public function authorize(): bool
    {
        return Gate::allows('permits', ['moderator', 'c']);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', "regex:{$this->emailRegex}", "unique:admins"],
            'password' => ['required', Password::default()->uncompromised(), 'confirmed'],
            'permissions' => ['required', 'json'],
        ];
    }
}
