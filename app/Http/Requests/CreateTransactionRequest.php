<?php

namespace App\Http\Requests;

use App\Http\ApiTraits\ExposeValidatorOnFail;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
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
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'transaction_code' => ['required', 'string', 'max:64'],
            'transaction_id' => ['required', 'string', 'max:64'],
            'transaction_time' => ['required', 'date'],
        ];
    }
}
