<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexOrderRequest extends FormRequest
{
    public function search(): ?string
    {
        $value = $this->query('search');

        return is_string($value) ? $value : null;
    }

    public function status(): ?string
    {
        $value = $this->query('status');

        return is_string($value) ? $value : null;
    }
}
