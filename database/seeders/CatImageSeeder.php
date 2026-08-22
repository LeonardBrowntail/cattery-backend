<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\CatImage;
use Illuminate\Database\Seeder;

class CatImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CatImage::factory()->count(Cat::all()->count())->create();
    }
}
