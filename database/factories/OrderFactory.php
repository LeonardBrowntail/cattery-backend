<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use MadeByClowd\Nusantara\Models\District;
use MadeByClowd\Nusantara\Models\Province;
use MadeByClowd\Nusantara\Models\Regency;
use MadeByClowd\Nusantara\Models\Village;

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
        $orderCount = Order::count();
        if ($orderCount < 1) {
            $user_id = User::all()->random()->id;
        } else {
            $ids = Order::get(['user_id']);
            $user_id = User::whereNotIn('id',$ids);
        }
        $user_id = User::all()->random();
        $province = Province::all()->random();
        $regency = Regency::where('province_id', $province->id)->get()->random();
        $district = District::where('regency_id', $regency->id)->get()->random();
        $village = Village::where('district_id', $district->id)->get()->random();
        return [
            'user_id' => $user_id,
            'province' => $province->name,
            'regency' => $regency->name,
            'district' => $district->name,
            'village' => $village->name,
            'address' => fake()->streetAddress(),
            'status' => fake()->randomElement([
                'pending', 'paid', 'sending', 'finished', 'cancelled'
            ])
        ];
    }
}