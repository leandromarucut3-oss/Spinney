<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvestmentPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Investment packages - Add your plans here
        $packages = [
            [
                'name' => 'Basic',
                'description' => 'Entry plan for new investors with steady daily returns.',
                'image' => 'Baic.jpeg',
                'min_amount' => 500.00,
                'max_amount' => 2499.00,
                'daily_interest_rate' => 0.5,
                'duration_days' => 150,
                'total_slots' => 500,
                'available_slots' => 500,
                'is_active' => true,
                'tier_required' => 'basic',
            ],
            [
                'name' => 'Standard',
                'description' => 'Balanced plan with higher daily interest for growing portfolios.',
                'image' => 'Standard.jpeg',
                'min_amount' => 2500.00,
                'max_amount' => 24900.00,
                'daily_interest_rate' => 0.7,
                'duration_days' => 120,
                'total_slots' => 300,
                'available_slots' => 300,
                'is_active' => true,
                'tier_required' => 'silver',
            ],
            [
                'name' => 'VIP',
                'description' => 'Exclusive plan for premium investors with top-tier daily returns.',
                'image' => 'VIP.jpeg',
                'min_amount' => 25000.00,
                'max_amount' => 25000.00,
                'daily_interest_rate' => 0.9,
                'duration_days' => 90,
                'total_slots' => 100,
                'available_slots' => 100,
                'is_active' => true,
                'tier_required' => 'platinum',
            ],
        ];

        foreach ($packages as $packageData) {
            \App\Models\InvestmentPackage::create($packageData);
        }
    }

    // OLD DATA REMOVED - Ready for fresh start
    private function oldData()
    {
        return [
            [
                'name' => 'Starter Plan',
                'description' => 'Perfect for beginners looking to start their investment journey',
                'image' => 'att.xBFsDBd84k7Emg9IXZ736suHeKlJ9jzrsZAsAcv1ndQ.jpeg',
                'min_amount' => 100.00,
                'max_amount' => 999.99,
                'daily_interest_rate' => 1.5,
                'duration_days' => 30,
                'total_slots' => 100,
                'available_slots' => 100,
                'is_active' => true,
                'tier_required' => 'basic',
            ],
            [
                'name' => 'Growth Plan',
                'description' => 'Accelerate your wealth with higher returns',
                'image' => 'att.xBFsDBd84k7Emg9IXZ736suHeKlJ9jzrsZAsAcv1ndQ.jpeg',
                'min_amount' => 1000.00,
                'max_amount' => 4999.99,
                'daily_interest_rate' => 2.0,
                'duration_days' => 60,
                'total_slots' => 75,
                'available_slots' => 75,
                'is_active' => true,
                'tier_required' => 'silver',
            ],
            [
                'name' => 'Premium Plan',
                'description' => 'Premium returns for serious investors',
                'image' => 'att.HZqOIC2SXNJF-eMhtrFy9xkTNQQM3NChxilUyiP0aSk.jpeg',
                'min_amount' => 5000.00,
                'max_amount' => 19999.99,
                'daily_interest_rate' => 2.5,
                'duration_days' => 90,
                'total_slots' => 50,
                'available_slots' => 50,
                'is_active' => true,
                'tier_required' => 'gold',
            ],
            [
                'name' => 'Platinum Elite',
                'description' => 'Exclusive plan for elite investors with maximum returns',
                'image' => 'att.HZqOIC2SXNJF-eMhtrFy9xkTNQQM3NChxilUyiP0aSk.jpeg',
                'min_amount' => 20000.00,
                'max_amount' => 100000.00,
                'daily_interest_rate' => 3.0,
                'duration_days' => 120,
                'total_slots' => 25,
                'available_slots' => 25,
                'is_active' => true,
                'tier_required' => 'platinum',
            ],
        ];

        foreach ($packages as $package) {
            \App\Models\InvestmentPackage::create($package);
        }
    }
}
