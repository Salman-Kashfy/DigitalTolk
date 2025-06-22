<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Assuming your User model is in App\Models
use Illuminate\Support\Facades\Hash; // For hashing the password

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an admin user if one doesn't already exist with the specific email
        User::firstOrCreate(
            [
                'email' => 'support@xyz.com'
            ],
            [
                'name' => 'admin',
                'password' => Hash::make('testing123'), // Use a strong default password here
                'email_verified_at' => now(), // Mark email as verified
            ]
        );

        $this->command->info('Admin user seeded (support@xyz.com / testing123)!');
    }
}
