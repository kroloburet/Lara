<?php

namespace App\Http\Requests\XHR\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateSecurityRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    protected string|null $emailRegex = null;
    public Admin|null $admin;

    protected function prepareForValidation(): void
    {
        $this->admin = $this->user('admin');
        $this->emailRegex = config('app.validation_rules.regex.email');
    }

    public function authorize(): bool
    {
        return !empty($this->admin);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                "regex:{$this->emailRegex}",
                Rule::unique('admins')->ignore($this->admin),
            ],
            'password' => ['nullable', Password::default()->uncompromised(), 'confirmed'],
        ];
    }
}
