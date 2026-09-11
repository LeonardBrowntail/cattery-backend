<?php

namespace App\Http\Requests;

use App\Http\ApiTraits\ExposeValidatorOnFail;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'username' => ['required', 'string', 'max:64'],
            'email' => ['required', 'string', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['string', 'max:16'],
        ];
    }
}
