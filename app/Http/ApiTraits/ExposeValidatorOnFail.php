<?php

namespace App\Http\ApiTraits;

trait ExposeValidatorOnFail
{
    /**
     * Exposed validator in case of a failed validation
     * @var \Illuminate\Contracts\Validation\Validator
     */
    public $validator = null;

    /**
     * Exposes failed validator
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator) {
        $this->validator = $validator;
    }
}
