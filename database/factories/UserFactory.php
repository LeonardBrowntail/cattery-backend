<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make(fake()->password(8)),
            'phone' => fake()->phoneNumber(),
            'is_admin' => false
        ];
    }

    /**
     * Changes the state of is_admin
     * @param bool $state set to `true` to make `user` admin, `false` otherwise
     * @return static
     */
    public function isAdmin(bool $state = true): static {
        return $this->state(fn (array $attributes) => [
            'is_admin' => $state
        ]);
    }
}
