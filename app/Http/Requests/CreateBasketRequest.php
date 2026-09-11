<?php

namespace App\Http\Requests;

use App\Http\ApiTraits\ExposeValidatorOnFail;
use App\Models\Cat;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CreateBasketRequest extends FormRequest
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
            'cat_id' => ['required', 'integer', 'exists:cats,id'],
        ];
    }

    /**
     * Checks if the cat is available.
     *
     * @param  Validator  $validator  Validator instance
     */
    protected function checkCatAvailability(Validator $validator): void
    {
        $catId = $this->integer('cat_id');
        $status = Cat::find($catId)->status;
        switch ($status) {
            case 'Reserved':
                $validator->errors()->add('cat_id', "Cat (id:{$catId}) is not for sale.");

                return;
            case 'Sold':
                $validator->errors()->add('cat_id', "Cat (id:{$catId}) is already sold.");

                return;
            default:
                return;
        }
    }

    /**
     * @return array<int, \Closure(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $this->checkCatAvailability($validator);
            },
        ];
    }
}
