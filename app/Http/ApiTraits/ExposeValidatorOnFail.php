<?php

namespace App\Http\ApiTraits;

use Illuminate\Contracts\Validation\Validator;

trait ExposeValidatorOnFail
{
    /**
     * Exposed validator in case of a failed validation
     *
     * @var Validator
     */
    public $validator = null;

    /**
     * Exposes failed validator
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
}
