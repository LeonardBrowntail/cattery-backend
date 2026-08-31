<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use SortDirection;

class IndexCatRequest extends FormRequest
{
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
        return [];
    }

    public function parents() {
        return $this->exists('parents');
    }

    public function search() {
        return $this->query('search');
    }

    public function sex() {
        return $this->query('sex');
    }

    public function status() {
        $str = $this->query('status');
        if (!$str) return null;
        return explode(',',$str);
    }

    public function sort() {
        $raw = $this->query('sort');
        if (str_contains($raw, 'price')) {
            return 'price';
        }
        if (str_contains($raw, 'age')) {
            return 'birthdate';
        }
        return 'updated_at';
    }

    public function sortDir() {
        if ($this->has('asc')) {
            return SortDirection::Ascending;
        }
        return SortDirection::Descending;
    }

    public function minPrice() {
        return $this->query('minPrice');
    }

    public function maxPrice() {
        return $this->query('maxPrice');
    }

    public function page() {
        return $this->query('page');
    }

    public function perPage() {
        return $this->integer('perPage', 12);
    }
}
