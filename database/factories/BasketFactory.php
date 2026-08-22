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
        $user = User::all()->random()->id;
        $cat = Cat::leftJoin('baskets', 'cats.id', '=', 'baskets.cat_id')->get();
        var_dump($cat);
        return [
            'user_id' => $user,
            'cat_id' => Cat::all()->random()->id
        ];
    }
}
