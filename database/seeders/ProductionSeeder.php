<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use MadeByClowd\Nusantara\Seeders\NusantaraCoreSeeder;

class ProductionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminSeeder = new AdminSeeder;
        $adminSeeder->run();

        $nusaSeeder = new NusantaraCoreSeeder;
        $nusaSeeder->run();
    }
}
