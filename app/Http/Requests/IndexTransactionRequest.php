<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexTransactionRequest extends FormRequest
{
    public function orderId(): ?int
    {
        $value = $this->query('order_id');

        return is_numeric($value) ? (int) $value : null;
    }

    public function search(): ?string
    {
        $value = $this->query('search');

        return is_string($value) ? $value : null;
    }
}
