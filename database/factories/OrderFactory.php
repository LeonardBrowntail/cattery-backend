<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $province = fake()->randomElement(['DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Bali', 'Jawa Timur', 'Sumatera Utara']);
        $regency = fake()->randomElement(['Kabupaten Bandung', 'Kota Surabaya', 'Kota Denpasar', 'Kota Medan', 'Kabupaten Bogor']);
        $district = fake()->randomElement(['Kecamatan Cibeunying', 'Kecamatan Sumedang Utara', 'Kecamatan Denpasar Selatan', 'Kecamatan Medan Baru']);
        $village = fake()->randomElement(['Desa Cibeunying', 'Kelurahan Sumedang', 'Desa Denpasar', 'Kelurahan Medan']);

        return [
            'user_id' => User::factory(),
            'province' => $province,
            'regency' => $regency,
            'district' => $district,
            'village' => $village,
            'address' => fake()->streetAddress(),
            'status' => fake()->randomElement([
                'pending', 'cancelled', 'sending', 'complete',
            ]),
        ];
    }
}
