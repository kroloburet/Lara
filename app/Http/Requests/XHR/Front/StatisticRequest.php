<?php

namespace App\Http\Requests\XHR\Front;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatisticRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    public Model|null $model = null;

    protected function prepareForValidation(): void
    {
        $this->model = materialBuilder($this->input('model_type'))
            ->find($this->input('model_id'));
    }

    public function authorize(): bool
    {
        return !empty($this->model);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                Rule::in(array_keys(config('app.materials.statistic', []))),
            ],
            'model_type' => [
                'required',
                Rule::in(array_keys(config('app.materials.types', []))),
            ],
            'model_id' => [
                'required',
                Rule::exists(config("app.materials.types.{$this->input('model_type')}.tableName"), 'id'),
            ],
        ];
    }
}
