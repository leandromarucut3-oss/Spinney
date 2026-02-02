<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register event listeners
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvestmentCreated::class,
            [
                \App\Listeners\ProcessReferralCommission::class,
                \App\Listeners\GenerateInvestmentReceipt::class,
            ]
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\UserRegistered::class,
            \App\Listeners\ProcessReferralBonus::class
        );
    }
}
