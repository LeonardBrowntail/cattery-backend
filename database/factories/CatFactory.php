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
            'sex' => $this->faker->randomElement(['male', 'female']),
            'color' => $this->faker->randomElement([
                'Black', 'White', 'Tabby', 'Calico', 'Ginger', 'Tortoiseshell', 'Grey'
            ]),
            'birthdate' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'weight' => $this->faker->randomFloat(2, 2.5, 8.0), // weight in kg
            'price' => $this->faker->numberBetween(500, 15000) * 1000,
            'description' => $this->faker->text(128),
            'status' => $this->faker->randomElement(['available', 'reserved', 'sold']),
        ];
    }

    public function withParents() : static {
        return $this->state(fn (array $attributes) => [
            'father_id' => Cat::where('sex', 'male')->inRandomOrder()->first()?->id ?? Cat::factory()->create(['sex' => 'male'])->id,
            'mother_id' => Cat::where('sex', 'female')->inRandomOrder()->first()?->id ?? Cat::factory()->create(['sex' => 'female'])->id
        ]);
    }
}
