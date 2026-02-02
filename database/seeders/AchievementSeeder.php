<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'name' => 'First Investment',
                'slug' => 'first-investment',
                'description' => 'Made your first investment',
                'icon' => '💰',
                'points' => 10,
                'requirements' => ['type' => 'investment_count', 'value' => 1],
                'is_active' => true,
            ],
            [
                'name' => 'Consistent Investor',
                'slug' => 'consistent-investor',
                'description' => 'Made 5 investments',
                'icon' => '📈',
                'points' => 50,
                'requirements' => ['type' => 'investment_count', 'value' => 5],
                'is_active' => true,
            ],
            [
                'name' => 'Referral Master',
                'slug' => 'referral-master',
                'description' => 'Referred 10 users',
                'icon' => '👥',
                'points' => 100,
                'requirements' => ['type' => 'referral_count', 'value' => 10],
                'is_active' => true,
            ],
            [
                'name' => 'Attendance Champion',
                'slug' => 'attendance-champion',
                'description' => '30-day login streak',
                'icon' => '🔥',
                'points' => 75,
                'requirements' => ['type' => 'attendance_streak', 'value' => 30],
                'is_active' => true,
            ],
            [
                'name' => 'High Roller',
                'slug' => 'high-roller',
                'description' => 'Total investments exceeding $10,000',
                'icon' => '💎',
                'points' => 200,
                'requirements' => ['type' => 'total_invested', 'value' => 10000],
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            \App\Models\Achievement::create($achievement);
        }
    }
}
