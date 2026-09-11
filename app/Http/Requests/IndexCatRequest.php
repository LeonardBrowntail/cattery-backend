<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexCatRequest extends FormRequest
{
    public function parents(): bool
    {
        return $this->exists('parents');
    }

    public function search(): ?string
    {
        $value = $this->query('search');

        return is_string($value) ? $value : null;
    }

    public function sex(): ?string
    {
        $value = $this->query('sex');

        return is_string($value) ? $value : null;
    }

    /** @return array<int, string>|string|null */
    public function status()
    {
        if ($this->user() == null || ! $this->user()->isAdmin()) {
            return 'Available';
        }

        $str = $this->query('status');
        if (! is_string($str) || $str === '') {
            return null;
        }

        return explode(',', $str);
    }

    public function sort(): string
    {
        $raw = (string) $this->query('sort');
        if (str_contains($raw, 'price')) {
            return 'price';
        }
        if (str_contains($raw, 'age')) {
            return 'birthdate';
        }

        return 'updated_at';
    }

    /**
     * @return 'asc'|'desc'
     */
    public function sortDir(): string
    {
        return $this->has('asc') ? 'asc' : 'desc';
    }

    public function minPrice(): ?string
    {
        $value = $this->query('minPrice');

        return is_string($value) ? $value : null;
    }

    public function maxPrice(): ?string
    {
        $value = $this->query('maxPrice');

        return is_string($value) ? $value : null;
    }

    public function page(): ?string
    {
        $value = $this->query('page');

        return is_string($value) ? $value : null;
    }

    public function perPage(): int
    {
        return $this->integer('perPage', 12);
    }
}
