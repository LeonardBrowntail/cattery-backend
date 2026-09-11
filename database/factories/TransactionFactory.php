<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => null,
            'transaction_code' => strtoupper(fake()->bothify('??####')),
            'transaction_id' => strtoupper(fake()->bothify('TXN####??')),
            'transaction_time' => fake()->dateTimeBetween('-30 days', 'now'),
            'amount' => fake()->numberBetween(500000, 10000000),
            'recorded_by' => null,
        ];
    }
}
