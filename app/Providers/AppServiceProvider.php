<?php

namespace App\Providers;

use App\Models\Deposit;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
        Blade::directive('money', function ($expression) {
            return "<?php echo app(\\App\\Services\\CurrencyService::class)->format($expression); ?>";
        });

        Blade::directive('currencyCode', function () {
            return "<?php echo app(\\App\\Services\\CurrencyService::class)->code(); ?>";
        });

        // Register event listeners
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvestmentCreated::class,
            \App\Listeners\ProcessReferralCommission::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\InvestmentCreated::class,
            \App\Listeners\GenerateInvestmentReceipt::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\UserRegistered::class,
            \App\Listeners\ProcessReferralBonus::class
        );

        View::composer('components.admin-layout', function ($view) {
            $newUsers = 0;
            $pendingDeposits = 0;
            $pendingWithdrawals = 0;

            if (auth()->check() && auth()->user()->is_admin) {
                $newUsers = User::where('created_at', '>=', now()->subDay())->count();
                $pendingDeposits = Deposit::where('status', 'pending')->count();
                $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
            }

            $view->with('adminNavCounts', [
                'newUsers' => $newUsers,
                'pendingDeposits' => $pendingDeposits,
                'pendingWithdrawals' => $pendingWithdrawals,
            ]);
        });
    }
}
