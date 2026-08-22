<?php

namespace Database\Factories;

use App\Models\Basket;
use App\Models\Cat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Basket>
 */
class BasketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->random();
        if ($user->baskets()->count() < 1) {
            $cat = Cat::all()->random()->id;
        } else {
            $basketCatIds = $user->baskets()->get('cat_id')->toArray();
            $cat = Cat::whereNotIn('id', $basketCatIds)->get()->random()->id;
        }
        return [
            'user_id' => $user->id,
            'cat_id' => $cat
        ];
    }
}
