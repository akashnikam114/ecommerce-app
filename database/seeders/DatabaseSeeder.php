<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Admin@123'),
            'is_admin' => true,
        ]);

        // Create App Customer
        \App\Models\User::factory()->create([
            'name' => 'Customer',
            'email' => 'user@example.com',
            'password' => Hash::make('User@123'),
            'is_admin' => false,
        ]);

        \App\Models\Product::factory(15)->create();
    }
}
