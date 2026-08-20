<?php

namespace App\Http\Requests;

use App\Http\ApiTraits\ExposeValidatorOnFail;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCatRequest extends FormRequest
{
    use ExposeValidatorOnFail;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'breed' => ['sometimes', 'string', 'max:255'],
            'sex' => ['sometimes', 'in:male,female'],
            'father_id' => ['nullable', 'integer', 'exists:cats,id'],
            'mother_id' => ['nullable', 'integer', 'exists:cats,id'],
            'birthdate' => ['nullable', 'date', 'before_or_equal:today'],
            'color' => ['nullable', 'string', 'max:255'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:available,reserved,sold'],
            'pedigree_info' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],

            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB each
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
