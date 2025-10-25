<?php

namespace App\Http\Requests\XHR;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class IsUniqueValueRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    protected function prepareForValidation(): void
    {
        $modelName = Relation::morphMap()[$this->input('model')] ?? null;

        if (!class_exists($modelName) || !is_subclass_of($modelName, Model::class)) {
            throw ValidationException::withMessages(['model' => 'Invalid model specified.']);
        }

        $this->merge(['modelName' => $modelName]);

        $columnName = $this->input('column');

        if (!Schema::hasColumn((new $modelName)->getTable(), $columnName)) {
            throw ValidationException::withMessages(['column' => 'Invalid column specified.']);
        }

        $this->merge(['columnName' => $columnName]);
    }

    public function authorize(): bool
    {
        return (
            $this->input('modelName') &&
            $this->input('columnName') &&
            $this->input('unique_value')
        );
    }

    public function rules(): array
    {
        $modelName = $this->input('modelName');
        $columnName = $this->input('columnName');
        $ignoreId = $this->input('ignore');

        return [
            'model' => [
                'required',
                'string',
                Rule::in(array_keys(Relation::morphMap())),
            ],
            'ignore' => [
                'sometimes',
                'numeric',
                $ignoreId ? "exists:{$modelName},id" : null,
            ],
            'column' => [
                'required',
                'string',
                'regex:/^[a-zA-Z0-9_]+$/',
            ],
            'unique_value' => [
                'required',
                'string',
                Rule::unique($modelName, $columnName)->ignore($ignoreId),
            ],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw ValidationException::withMessages($validator->errors()->toArray());
    }
}
