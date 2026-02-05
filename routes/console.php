<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

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

