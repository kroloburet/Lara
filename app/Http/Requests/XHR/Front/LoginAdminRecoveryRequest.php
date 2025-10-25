<?php

namespace App\Http\Requests\XHR\Front;

use Illuminate\Foundation\Http\FormRequest;

class LoginAdminRecoveryRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    protected string|null $emailRegex = null;

    protected function prepareForValidation(): void
    {
        $this->emailRegex = config('app.validation_rules.regex.email');
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', "regex:{$this->emailRegex}"],
        ];
    }
}
