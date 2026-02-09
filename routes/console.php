<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\Investment;
use App\Models\InterestLog;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('interest:backfill {--from=} {--to=} {--user=} {--investment=} {--dry-run}', function () {
    $fromOption = $this->option('from');
    $toOption = $this->option('to');
    $userId = $this->option('user');
    $investmentId = $this->option('investment');
    $dryRun = (bool) $this->option('dry-run');

    $from = $fromOption ? Carbon::parse($fromOption)->startOfDay() : now()->subDay()->startOfDay();
    $to = $toOption ? Carbon::parse($toOption)->startOfDay() : $from->copy();

    if ($from->gt($to)) {
        $this->error('The --from date must be before or equal to --to.');
        return 1;
    }

    $query = Investment::where('status', 'active')
        ->where('start_date', '<=', $to)
        ->where('maturity_date', '>', $from)
        ->with('user');

    if ($userId) {
        $query->where('user_id', $userId);
    }

    if ($investmentId) {
        $query->where('id', $investmentId);
    }

    $investments = $query->get();
    $totalApplied = 0;
    $totalSkipped = 0;

    foreach ($investments as $investment) {
        $current = $from->copy();
        while ($current->lte($to)) {
            $calcDate = $current->toDateString();

            if ($current->lt($investment->start_date) || $current->gte($investment->maturity_date)) {
                $current->addDay();
                $totalSkipped++;
                continue;
            }

            $alreadyProcessed = InterestLog::where('investment_id', $investment->id)
                ->where('calculation_date', $calcDate)
                ->where('status', 'processed')
                ->exists();

            if ($alreadyProcessed) {
                $current->addDay();
                $totalSkipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("[dry-run] {$investment->id} {$calcDate}");
                $totalApplied++;
                $current->addDay();
                continue;
            }

            DB::transaction(function () use ($investment, $calcDate, &$totalApplied) {
                $user = $investment->user;
                $balanceBefore = $user->balance;

                $interestAmount = $investment->daily_interest;
                $user->addBalance(
                    $interestAmount,
                    'daily_interest',
                    "Daily interest from investment {$investment->receipt_number} (backfill {$calcDate})",
                    $investment
                );

                $investment->total_earned += $interestAmount;
                $investment->save();

                InterestLog::create([
                    'investment_id' => $investment->id,
                    'user_id' => $user->id,
                    'calculation_date' => $calcDate,
                    'interest_amount' => $interestAmount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $user->fresh()->balance,
                    'status' => 'processed',
                ]);

                $totalApplied++;
            });

            $current->addDay();
        }
    }

    $this->info("Backfill complete. Applied: {$totalApplied}, Skipped: {$totalSkipped}");

    return 0;
})->purpose('Backfill daily interest for a date range');

// Schedule daily interest processing
Schedule::job(new \App\Jobs\ProcessDailyInterest)
    ->daily()
    ->name('process-daily-interest')
    ->withoutOverlapping();

// Schedule investment maturity processing
Schedule::job(new \App\Jobs\ProcessInvestmentMaturity)
    ->dailyAt('01:00')
    ->name('process-investment-maturity')
    ->withoutOverlapping();

// Schedule monthly raffle draws (first day of each month)
Schedule::job(new \App\Jobs\DrawMonthlyRaffle)
    ->monthlyOn(1, '02:00')
    ->name('draw-monthly-raffle')
    ->withoutOverlapping();

