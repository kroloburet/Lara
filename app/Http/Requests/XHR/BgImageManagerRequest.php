<?php

namespace App\Http\Requests\XHR;

use App\Models\Abstract\Material;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BgImageManagerRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    public Material|null $material = null;

    protected function prepareForValidation(): void
    {
        $this->material = materialBuilder($this->input('material_type'))
            ->find($this->input('material_id'));
    }

    public function authorize(): bool
    {
        return (
            auth()->check() &&
            !empty($this->material)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'material_type' => [
                'required',
                Rule::in(array_keys(config('app.materials.types', []))),
            ],
            'material_id' => [
                'required',
                'integer',
            ],
            'bg_image' => [
                'sometimes',
                'required',
                'string', // Base64
            ],
        ];
    }
}
