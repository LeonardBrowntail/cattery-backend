<?php

namespace App\Http\Requests;

use App\Http\ApiTraits\ExposeValidatorOnFail;
use App\Models\Cat;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateCatRequest extends FormRequest
{
    use ExposeValidatorOnFail;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'breed' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'in:male,female'],
            'father_id' => ['nullable', 'integer', 'exists:cats,id'],
            'mother_id' => ['nullable', 'integer', 'exists:cats,id'],
            'birthdate' => ['nullable', 'date', 'before_or_equal:today'],
            'color' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:available,reserved,sold'],
            'pedigree_info' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],

            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB each
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator) {
        $validator->after(function(Validator $validator) {
            $this->assertNotSelf($validator, 'father_id');
            $this->assertNotSelf($validator, 'mother_id');
            $this->assertParentSex($validator, 'father_id', 'male');
            $this->assertParentSex($validator, 'mother_id', 'female');
        });
    }

    protected function assertNotSelf(Validator $validator, string $field) {
        $id = $this->input($field);
        if (!$id) {
            return;
        }
        $routeId = $this->route('id');
        if (!$routeId) {
            return;
        }

        if ((int) $id === (int) $routeId) {
            $validator->errors()->add($field, 'A cat cannot be its own parent');
        }
    }

    protected function assertParentSex(Validator $validator, string $field, string $expectedSex) {
        $id = $this->input($field);

        if (!$id) {
            return;
        }

        $sex = Cat::whereKey($id)->value('sex');

        if ($sex !== null && $sex !== $expectedSex) {
            $validator->errors()->add('$field', "{$field} must belong to a {$expectedSex} cat.");
        }
    }
}
