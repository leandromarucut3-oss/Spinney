<?php

namespace App\Jobs;

use App\Models\Investment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessInvestmentMaturity implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $processedCount = 0;
        $errorCount = 0;

        Log::info('Starting investment maturity processing');

        $maturedInvestments = Investment::where('status', 'active')
            ->where('maturity_date', '<=', now()->toDateString())
            ->with('user')
            ->get();

        foreach ($maturedInvestments as $investment) {
            try {
                DB::transaction(function () use ($investment) {
                    $investment->complete();
                });

                Log::info("Investment {$investment->receipt_number} matured for user {$investment->user->name}");
                $processedCount++;
            } catch (\Exception $e) {
                Log::error("Failed to process maturity for investment {$investment->id}: {$e->getMessage()}");
                $errorCount++;
            }
        }

        Log::info("Investment maturity processing completed. Processed: {$processedCount}, Errors: {$errorCount}");
    }
}
