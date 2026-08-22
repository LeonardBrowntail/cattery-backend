<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
        $seeder = null;
        if (app()->isProduction()) {
            $seeder = new ProductionSeeder();
        } else {
            $seeder = new DevSeeder();
        }
        $seeder->run();
    }
}