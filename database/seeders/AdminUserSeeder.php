<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@spinneys.com'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'pin' => '123456',
                'phone' => '+1234567890',
                'balance' => 10000000.00,
                'referral_code' => 'ADMIN001',
                'tier' => 'platinum',
                'is_verified' => true,
                'is_suspended' => false,
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        \App\Models\User::updateOrCreate(
            ['email' => 'user@spinneys.com'],
            [
                'name' => 'Test User',
                'username' => 'testuser',
                'password' => bcrypt('password'),
                'pin' => '123456',
                'phone' => '+1234567891',
                'balance' => 1000.00,
                'referral_code' => 'USER001',
                'tier' => 'basic',
                'is_verified' => true,
                'is_suspended' => false,
                'is_admin' => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
