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
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@spinneys.com',
            'password' => bcrypt('password'),
            'pin' => '123456',
            'phone' => '+1234567890',
            'balance' => 0,
            'referral_code' => 'ADMIN001',
            'tier' => 'platinum',
            'is_verified' => true,
            'is_suspended' => false,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'user@spinneys.com',
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
        ]);
    }
}
