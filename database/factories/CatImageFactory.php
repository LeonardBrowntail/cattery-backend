<?php

namespace Database\Factories;

use App\Models\Cat;
use App\Models\CatImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends Factory<CatImage>
 */
class CatImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cat_id' => null,
            'path' => null,
            'is_primary' => false
        ];
    }

    /**
     * Generate .
     */
    public function addPath(string $path): static
    {
        return $this->state(fn (array $attributes) => [
            'path' => $path,
        ]);
    }
}
