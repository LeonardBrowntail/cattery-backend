<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use MadeByClowd\Nusantara\Seeders\NusantaraCoreSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        // seed admins
        $adminSeeder = new AdminSeeder();
        $adminSeeder->run();

        // seed users
        $userSeeder = new UserSeeder();
        $userSeeder->run();

        // seed cats
        $catSeeder = new CatSeeder();
        $catSeeder->run();

        // seed nusantara
        $nusaSeeder = new NusantaraCoreSeeder();
        $nusaSeeder->run();

        // seed cat images
        $catImgSeeder = new CatImageSeeder();
        $catImgSeeder->run();

        // seed baskets
        $basketSeeder = new BasketSeeder();
        $basketSeeder->run();

        // seed orders
        $orderSeeder = new OrderSeeder();
        $orderSeeder->run();

        // seed order details
        $ordDetSeeder = new OrderDetailsSeeder();
        $ordDetSeeder->run();
    }
}
