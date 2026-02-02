<?php

namespace App\Jobs;

use App\Models\Raffle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DrawMonthlyRaffle implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting monthly raffle draw');

        $activeRaffles = Raffle::where('status', 'active')
            ->where('draw_date', '<=', now()->toDateString())
            ->get();

        foreach ($activeRaffles as $raffle) {
            try {
                DB::transaction(function () use ($raffle) {
                    $winner = $raffle->drawWinner();

                    if ($winner) {
                        Log::info("Raffle '{$raffle->title}' won by user {$winner->name} (ID: {$winner->id})");

                        // Mark raffle as completed
                        $raffle->status = 'completed';
                        $raffle->save();
                    } else {
                        Log::warning("No entries for raffle '{$raffle->title}' (ID: {$raffle->id})");
                    }
                });
            } catch (\Exception $e) {
                Log::error("Failed to draw raffle {$raffle->id}: {$e->getMessage()}");
            }
        }

        Log::info('Monthly raffle draw completed');
    }
}
