<?php

namespace App\Jobs;

use App\Models\Investment;
use App\Models\InterestLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessDailyInterest implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $processedCount = 0;
        $errorCount = 0;
        $today = now()->toDateString();
        $lock = Cache::lock("daily-interest-{$today}", 3600);

        if (! $lock->get()) {
            Log::info("Daily interest already running for {$today}");
            return;
        }

        Log::info('Starting daily interest processing');

        try {
            $activeInvestments = Investment::where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('maturity_date', '>', now())
                ->with(['user', 'package'])
                ->get();

            foreach ($activeInvestments as $investment) {
                try {
                    $alreadyProcessed = InterestLog::where('investment_id', $investment->id)
                        ->where('calculation_date', $today)
                        ->where('status', 'processed')
                        ->exists();

                    if ($alreadyProcessed) {
                        continue;
                    }

                    DB::transaction(function () use ($investment) {
                        $user = $investment->user;
                        $balanceBefore = $user->balance;

                        // Calculate and add interest
                        $interestAmount = $investment->daily_interest;
                        $user->addBalance(
                            $interestAmount,
                            'daily_interest',
                            "Daily interest from investment {$investment->receipt_number}",
                            $investment
                        );

                        // Update investment total earned
                        $investment->total_earned += $interestAmount;
                        $investment->save();

                        // Log the interest calculation
                        InterestLog::create([
                            'investment_id' => $investment->id,
                            'user_id' => $user->id,
                            'calculation_date' => now()->toDateString(),
                            'interest_amount' => $interestAmount,
                            'balance_before' => $balanceBefore,
                            'balance_after' => $user->fresh()->balance,
                            'status' => 'processed',
                        ]);
                    });

                    $processedCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to process interest for investment {$investment->id}: {$e->getMessage()}");
                    $errorCount++;

                    // Log failed interest calculation
                    InterestLog::create([
                        'investment_id' => $investment->id,
                        'user_id' => $investment->user_id,
                        'calculation_date' => now()->toDateString(),
                        'interest_amount' => 0,
                        'balance_before' => $investment->user->balance,
                        'balance_after' => $investment->user->balance,
                        'status' => 'failed',
                    ]);
                }
            }

            Log::info("Daily interest processing completed. Processed: {$processedCount}, Errors: {$errorCount}");
        } finally {
            optional($lock)->release();
        }
    }
}
