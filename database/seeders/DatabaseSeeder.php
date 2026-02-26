<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = 'admin@example.com';

        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => $adminEmail,
                'password' => Hash::make('aes@ispscadmin'), // Change after first login
                'role' => 'admin', // Assign admin role
                'otp_verified' => true,
            ]);

            $this->command->info('Admin user created: ' . $adminEmail . ' / aes@ispscadmin');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}
