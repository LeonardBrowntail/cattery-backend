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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'father_id' => ['nullable', 'integer', 'exists:cats,id'],
            'mother_id' => ['nullable', 'integer', 'exists:cats,id'],
            'name' => ['required', 'string', 'max:128'],
            'sex' => ['required', 'in:Male,Female'],
            'breed' => ['required', 'string', 'max:64'],
            'color' => ['required', 'string', 'max:64'],
            'birthdate' => ['required','nullable', 'date', 'before_or_equal:today', 'date_format:Y-m-d'],
            'price' => ['required', 'numeric', 'max_digits:9'],
            'status' => ['required', 'string', 'in:Available,Reserved,Sold'],
            'pedigree_info' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
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
            $validator->errors()->add($field, "Cat with id {$id} not found.");
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
        $id = $this->input($field);
        if (!$id) return;

        $cat = Cat::whereKey($id)->value('birthdate');
        if (!$cat) {
            $validator->errors()->add($field, "Cat with id {$id} not found.");
            return;
        }

        $birthdate = $this->input('birthdate');
        if (!$birthdate) {
            $validator->errors()->add($field, "Cat birthdate cannot be fethched.");
            return;
        }

        $requestBirthdate = date_create($this->input('birthdate'));
        $parentBirthdate = date_create(Cat::whereKey($id)->value('birthdate'));

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