<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('is_admin', false)->get();

        if ($customers->isEmpty()) {
            return;
        }

        $availableCats = Cat::where('status', 'Available')->get();

        if ($availableCats->isEmpty()) {
            return;
        }

        $orders = Order::factory()->count(10)->create([
            'user_id' => fn () => $customers->random()->id,
        ]);

        foreach ($orders as $index => $order) {
            $cats = $availableCats->shuffle()->take(rand(1, 2));

            foreach ($cats as $cat) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'cat_id' => $cat->id,
                    'price' => $cat->price,
                ]);
            }
        }
    }
}
