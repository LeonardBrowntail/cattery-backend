<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => "admin",
            'email' => "admin@miracattery.com",
            'password' => bcrypt('adminPassword123'),
            'is_admin' => true,
            'phone' => "081295226757"
        ]);
    }
}
