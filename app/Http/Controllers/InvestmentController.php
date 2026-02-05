<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Events\InvestmentCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestmentController extends Controller
{
    public function packages()
    {
        $packages = InvestmentPackage::where('is_active', true)
            ->orderBy('min_amount')
            ->get();

        return view('investments.packages', compact('packages'));
    }

    public function show(InvestmentPackage $package)
    {
        return view('investments.show', compact('package'));
    }

    public function invest(Request $request, InvestmentPackage $package)
    {
        if ($request->filled('amount')) {
            $request->merge([
                'amount' => app(\App\Services\CurrencyService::class)
                    ->convertToBase((float) $request->input('amount')),
            ]);
        }

        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:' . $package->min_amount,
                'max:' . $package->max_amount,
            ],
        ]);

        $amount = $request->amount;
        $user = auth()->user();

        // Check if user can invest in this package
        if (!$user->canInvestInPackage($package)) {
            return back()->with('error', 'Your tier does not allow investment in this package.');
        }

        // Check if user has sufficient balance
        if ($user->balance < $amount) {
            return back()->with('error', 'Insufficient balance. Please deposit funds first.');
        }

        DB::beginTransaction();
        try {
            // Try to decrement slot atomically
            if (!$package->decrementSlot()) {
                DB::rollBack();
                return back()->with('error', 'No slots available for this package.');
            }

            // Deduct balance from user
            $user->deductBalance($amount, 'investment', 'Investment in ' . $package->name);

            // Calculate investment details
            $dailyInterest = $package->calculateDailyInterest($amount);
            $totalReturn = $package->calculateTotalReturn($amount);
            $startDate = now();
            $maturityDate = $startDate->copy()->addDays($package->duration_days);

            // Create investment
            $investment = Investment::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $amount,
                'daily_interest' => $dailyInterest,
                'total_return' => $totalReturn,
                'total_earned' => 0,
                'duration_days' => $package->duration_days,
                'start_date' => $startDate,
                'maturity_date' => $maturityDate,
                'status' => 'active',
            ]);

            // Fire investment created event for referral commissions and receipt generation
            event(new InvestmentCreated($investment));

            DB::commit();

            return redirect()->route('investments.index')
                ->with('success', 'Investment created successfully! Receipt #' . $investment->receipt_number);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Investment creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create investment. Please try again.');
        }
    }

    public function index()
    {
        $investments = auth()->user()->investments()
            ->with('package')
            ->latest()
            ->paginate(10);

        $packages = InvestmentPackage::where('is_active', true)
            ->orderBy('min_amount')
            ->get();

        return view('investments.index', compact('investments', 'packages'));
    }

    public function upgrade(Request $request, InvestmentPackage $package)
    {
        $user = auth()->user();

        $activeInvestments = $user->investments()
            ->where('status', 'active')
            ->with('package')
            ->get();

        if ($activeInvestments->isEmpty()) {
            return back()->with('error', 'You have no active investments to upgrade.');
        }

        $totalAmount = $activeInvestments->sum('amount');

        if ($totalAmount < $package->min_amount) {
            return back()->with('error', 'Total active investments are below the minimum for this package.');
        }

        if ($totalAmount > $package->max_amount) {
            return back()->with('error', 'Total active investments exceed the maximum for this package.');
        }

        DB::beginTransaction();
        try {
            if (! $package->decrementSlot()) {
                DB::rollBack();
                return back()->with('error', 'No slots available for this package.');
            }

            foreach ($activeInvestments as $investment) {
                $investment->status = 'completed';
                $investment->save();
                $investment->package?->incrementSlot();
            }

            $dailyInterest = $package->calculateDailyInterest($totalAmount);
            $totalReturn = $package->calculateTotalReturn($totalAmount);
            $startDate = now();
            $maturityDate = $startDate->copy()->addDays($package->duration_days);

            $newInvestment = Investment::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'amount' => $totalAmount,
                'daily_interest' => $dailyInterest,
                'total_return' => $totalReturn,
                'total_earned' => 0,
                'duration_days' => $package->duration_days,
                'start_date' => $startDate,
                'maturity_date' => $maturityDate,
                'status' => 'active',
            ]);

            event(new InvestmentCreated($newInvestment));

            DB::commit();

            return back()->with('success', 'Investments upgraded successfully! Receipt #' . $newInvestment->receipt_number);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Investment upgrade failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to upgrade investments. Please try again.');
        }
    }
}
