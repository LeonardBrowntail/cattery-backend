<?php

namespace Database\Seeders;

use App\Models\Cat;
use App\Models\CatImage;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CatImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = 'cats/placeholder.svg';
        Storage::disk('public')->put($path, <<<'SVG'
            <svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480">
                <rect width="640" height="480" fill="#e5e7eb"/>
                <circle cx="320" cy="240" r="120" fill="#9ca3af"/>
                <path d="M235 165 220 80l75 55m110 30 15-85-75 55" fill="#9ca3af" stroke="#6b7280" stroke-width="16" stroke-linejoin="round"/>
                <circle cx="280" cy="225" r="12" fill="#374151"/>
                <circle cx="360" cy="225" r="12" fill="#374151"/>
                <path d="M305 270q15 15 30 0m-15 0v25m-75-25-70-15m70 40-70 15m240-15 70-15m-70 40 70 15" fill="none" stroke="#374151" stroke-width="8" stroke-linecap="round"/>
            </svg>
            SVG
        );
        CatImage::factory()->count(Cat::count())->addPath($path)->sequence(fn(Sequence $sequence) => ['cat_id' => $sequence->index + 1])->create();
    }
}