<?php

namespace App\Http\Requests\XHR\Email;

use Illuminate\Foundation\Http\FormRequest;

class AppealRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    protected string|null $emailRegex = null;
    protected string|null $phoneRegex = null;

    protected function prepareForValidation(): void
    {
        $this->emailRegex = config('app.validation_rules.regex.email');
        $this->phoneRegex = config('app.validation_rules.regex.phone');
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required_if:phone,null', 'nullable', "regex:{$this->emailRegex}"],
            'phone' => ['required_if:email,null', 'nullable', "regex:{$this->phoneRegex}"],
            'appeal.theme' => ['required', 'string'],
            'appeal.message' => ['required', 'string', 'max:500'],
        ];
    }
}
