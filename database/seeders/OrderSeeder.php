<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::factory()->count(10)->create();

        $paidOrders = $orders->filter(function (Order $order) {
            return in_array($order->status, ['paid', 'sending', 'cancelled']);
        });

        $usedCats = [];
        foreach ($paidOrders as $order) {
            $cat = Cat::all()->random();
            OrderDetail::factory()->create([
                'order_id' => $order->id,
                'cat_id' => $cat->id,
                'price' => $cat->price
            ]);
            array_push($usedCats, $cat->id);
        }

        $unpaidOrders = $orders->filter(function (Order $order) {
            return in_array($order->status, ['pending', 'cancelled']);
        });

        foreach ($unpaidOrders as $order) {
            $cat = Cat::whereNotIn('id', $usedCats)->get()->random();
            OrderDetail::factory()->create([
                'order_id' => $order->id,
                'cat_id' => $cat->id,
                'price' => $cat->price
            ]);
        }
    }
}
