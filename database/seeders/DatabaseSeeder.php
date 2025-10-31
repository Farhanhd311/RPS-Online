<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default admin user for login
        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Administrator',
                'role' => 'admin',
                'password' => 'password', // hashed by model cast
            ]
        );
    }
}
