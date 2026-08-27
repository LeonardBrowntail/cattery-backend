<?php

namespace Database\Factories;

use App\Models\Cat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cat>
 */
class CatFactory extends Factory
{
    protected $model = Cat::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'father_id' => null,
            'mother_id' => null,
            'name' => $this->faker->firstName(),
            'breed' => $this->faker->randomElement([
                'Persian', 'Maine Coon', 'Siamese', 'Ragdoll', 
                'Bengal', 'Sphynx', 'British Shorthair', 'Abyssinian'
            ]),
            'sex' => $this->faker->randomElement(['Male', 'Female']),
            'color' => $this->faker->randomElement([
                'Black', 'White', 'Tabby', 'Calico', 'Ginger', 'Tortoiseshell', 'Grey'
            ]),
            'birthdate' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'price' => $this->faker->numberBetween(100, 999) * 100000,
            'description' => $this->faker->text(128),
            'status' => $this->faker->randomElement(['Available', 'Reserved', 'Sold']),
        ];
    }

    public function older(): static {
        return $this->state(fn (array $attributes) => [
            'birthdate' => $this->faker->dateTimeBetween('-5 years', '-2 years')->format('Y-m-d')
        ]);
    }

    /**
     * Make the factory cat status to be "Reserved".
     * @return CatFactory
     */
    public function reserved(): static {
        return $this->state(fn (array $attributes) => [
            'status' => 'Reserved'
        ]);
    }

    /**
     * Make the factory cat status to be "Sold".
     * @return CatFactory
     */
    public function sold(): static {
        return $this->state(fn (array $attributes) => [
            'status' => 'Sold'
        ]);
    }
}
