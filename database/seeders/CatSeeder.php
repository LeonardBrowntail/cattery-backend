<?php

namespace Database\Seeders;

use App\Models\Cat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cat::factory()->count(10)->create();

        Cat::factory()->count(20)->create()->each(function ($cat) {
            if (rand(1,100) <= 30) {
                $cat->update([
                    'father_id' => Cat::where('sex', 'male')->where('id', '!=', $cat->id)->inRandomOrder()->first()?->id,
                    'mother_id' => Cat::where('sex', 'female')->where('id', '!=', $cat->id)->inRandomOrder()->first()?->id,
                ]);
            }
        });
    }
}
