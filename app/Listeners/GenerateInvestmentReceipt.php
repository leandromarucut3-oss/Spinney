<?php

namespace App\Listeners;

use App\Events\InvestmentCreated;
use App\Models\InvestmentReceipt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class GenerateInvestmentReceipt implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(InvestmentCreated $event): void
    {
        $investment = $event->investment;

        try {
            $receiptData = [
                'brand' => 'SPINNEYS',
                'investment' => [
                    'receipt_number' => $investment->receipt_number,
                    'amount' => $investment->amount,
                    'package_name' => $investment->package->name,
                    'daily_interest' => $investment->daily_interest,
                    'duration_days' => $investment->duration_days,
                    'start_date' => $investment->start_date->format('Y-m-d'),
                    'maturity_date' => $investment->maturity_date->format('Y-m-d'),
                    'expected_return' => $investment->total_return,
                ],
                'user' => [
                    'name' => $investment->user->name,
                    'email' => $investment->user->email,
                    'tier' => $investment->user->tier,
                ],
                'generated_at' => now()->toDateTimeString(),
            ];

            InvestmentReceipt::create([
                'investment_id' => $investment->id,
                'user_id' => $investment->user_id,
                'receipt_number' => $investment->receipt_number,
                'receipt_data' => $receiptData,
                'generated_at' => now(),
            ]);

            Log::info("Investment receipt generated: {$investment->receipt_number}");
        } catch (\Exception $e) {
            Log::error("Failed to generate receipt: {$e->getMessage()}");
        }
    }
}
