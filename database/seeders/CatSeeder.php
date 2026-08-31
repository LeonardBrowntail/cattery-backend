<?php

namespace Database\Seeders;

use App\Models\Cat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cat::factory()->male()->older()->reserved()->count(5)->create();
        Cat::factory()->female()->older()->reserved()->count(5)->create();

        Cat::factory()->count(20)->create()->each(function ($cat) {
            $query = function (string $gender) use ($cat) {
                return Cat::where([
                    ['id', '!=', $cat->id],
                    ['sex', '=', $gender],
                    ['birthdate', '<', $cat->birthdate]
                ])->get()->random()->id;
            };
            $cat->update([
                'father_id' => $query('Male'),
                'mother_id' => $query('Female')
            ]);
        });
    }
}