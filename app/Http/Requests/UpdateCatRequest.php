<?php

namespace App\Http\Requests;

use App\Http\ApiTraits\ExposeValidatorOnFail;
use App\Models\Cat;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateCatRequest extends FormRequest
{
    use ExposeValidatorOnFail;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'sex' => ['sometimes', 'in:Male,Female'],
            'father_id' => ['sometimes','nullable', 'integer', 'exists:cats,id'],
            'mother_id' => ['sometimes','nullable', 'integer', 'exists:cats,id'],
            'birthdate' => ['sometimes','nullable', 'date', 'before_or_equal:today'],
            'color' => ['sometimes','nullable', 'string', 'max:255'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'nullable', 'in:Available,Reserved,Sold'],
            'pedigree_info' => ['sometimes','nullable', 'string'],
            'description' => ['sometimes','nullable', 'string'],

            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB each
            'primary_image_index' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Asserts the cat's parent sex.
     * @param Validator $validator Validator instance.
     * @param string $field Request field.
     * @param string $expectedSex Expected sex of the parent.
     * @return void
     */
    protected function assertParentSex(Validator $validator, string $field, string $expectedSex) {
        $id = $this->input($field);
        if (!$id) return;

        $sex = Cat::whereKey($id)->value('sex');
        if (!$sex) {
            $validator->errors()->add($field, "Cat with id {$field} not found.");
            return;
        }

        if ($sex !== null && $sex !== $expectedSex) {
            $validator->errors()->add($field, "{$field} must belong to a {$expectedSex} cat.");
        }
    }

    /**
     * Asserts the parents to be older than the cat.
     * @param Validator $validator Validator instance.
     * @param string $field Request field.
     * @return void
     */
    protected function assertLogicalAge(Validator $validator, string $field) {
        $parentId = $this->input($field);
        if (!$parentId) return;

        $catId = $this->route('cat');
        $cat = Cat::find($catId);
        if (!$cat) {
            $validator->errors()->add($field, "Unable to fetch cat with id {$catId}.");
            return;
        }

        $parent = Cat::find($parentId);
        if (!$parent) {
            $validator->errors()->add($field, "Cat with id {$parentId} not found.");
            return;
        }

        $requestBirthdate = date_create($this->input('birthdate'));
        $parentBirthdate = date_create($parent->birthdate);

        $diff = (int) date_diff($requestBirthdate, $parentBirthdate)->format('%r%a');
        if ($diff >= -180) {
            $validator->errors()->add($field, "parent must be at least 180 days (6 months) older, the specified parent is only {$diff} days old");
        }
    }

    public function after(): array {
        return [
            function (Validator $validator) {
                $this->assertParentSex($validator, 'father_id', 'Male');
                $this->assertParentSex($validator, 'mother_id', 'Female');
            }, 
            function (Validator $validator) {
                $this->assertLogicalAge($validator, 'father_id');
                $this->assertLogicalAge($validator, 'mother_id');
            }
        ];
    }
}
