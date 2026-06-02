<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default test user if it doesn't exist
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Seed some customers
        if (\App\Models\Customer::count() === 0) {
            \App\Models\Customer::factory(10)->create();
        }

        // Seed some proposals
        if (\App\Models\Proposal::count() === 0) {
            \App\Models\Proposal::factory(15)->create();
        }
    }
}
