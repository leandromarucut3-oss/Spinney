<?php

namespace App\Http\Controllers;

use App\Events\InvestmentCreated;
use App\Models\Deposit;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Models\InterestLog;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->withSum(['deposits as total_deposits' => function ($query) {
                $query->where('status', 'approved');
            }], 'amount')
            ->withSum(['investments as total_invested' => function ($query) {
                $query->where('status', 'active');
            }], 'amount')
            ->orderBy('created_at', 'desc')
            ->get();

        $pendingDeposits = Deposit::query()
            ->with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pendingWithdrawals = Withdrawal::query()
            ->with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $packages = InvestmentPackage::query()
            ->where('is_active', true)
            ->orderBy('min_amount')
            ->get();

        return view('admin.dashboard', compact('users', 'pendingDeposits', 'pendingWithdrawals', 'packages'));
    }

    public function users()
    {
        $users = User::query()
            ->with(['investments' => function ($query) {
                $query->with('package')
                    ->orderBy('start_date', 'desc');
            }])
            ->withSum(['deposits as total_deposits' => function ($query) {
                $query->where('status', 'approved');
            }], 'amount')
            ->withSum(['investments as total_invested' => function ($query) {
                $query->where('status', 'active');
            }], 'amount')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', compact('users'));
    }

    public function deposits()
    {
        $pendingDeposits = Deposit::query()
            ->with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.deposits', compact('pendingDeposits'));
    }

    public function withdrawals()
    {
        $pendingWithdrawals = Withdrawal::query()
            ->with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.withdrawals', compact('pendingWithdrawals'));
    }

    public function approveDeposit(Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit is not pending.');
        }
        try {
            $deposit->approve(request()->user());
            return back()->with('success', 'Deposit approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rejectDeposit(Request $request, Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return back()->with('error', 'Deposit is not pending.');
        }

        $deposit->reject($request->user(), $request->input('admin_notes'));

        return back()->with('success', 'Deposit rejected successfully.');
    }

    public function approveWithdrawal(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal is not pending.');
        }

        $withdrawal->approve(request()->user());

        return back()->with('success', 'Withdrawal approved successfully.');
    }

    public function rejectWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal is not pending.');
        }

        DB::transaction(function () use ($request, $withdrawal) {
            $withdrawal->reject($request->user(), $request->input('admin_notes'));
        });

        return back()->with('success', 'Withdrawal rejected and funds returned.');
    }

    public function transferFunds(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'message' => 'nullable|string|max:255',
        ]);

        $admin = $request->user();
        $recipient = User::findOrFail($request->input('user_id'));
        $amount = (float) $request->input('amount');

        if ($admin->balance < $amount) {
            return back()->with('error', 'Insufficient admin funds for this transfer.');
        }

        $note = $request->input('message') ?: 'Admin transfer to ' . $recipient->name;

        try {
            $admin->deductBalance($amount, 'admin_transfer', $note, $recipient);
            $recipient->addBalance($amount, 'admin_transfer', $note, $admin);
        } catch (\Exception $e) {
            return back()->with('error', 'Transfer failed. Please try again.');
        }

        return back()->with('success', 'Transfer completed successfully.');
    }

    public function activateInvestment(Request $request)
    {
        if ($request->filled('amount')) {
            $request->merge([
                'amount' => app(\App\Services\CurrencyService::class)
                    ->convertToBase((float) $request->input('amount')),
            ]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:investment_packages,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $package = InvestmentPackage::where('is_active', true)
            ->whereKey($request->input('package_id'))
            ->first();
        $amount = (float) $request->input('amount');

        if (! $package) {
            return back()->with('error', 'Selected package is not active.');
        }

        if (! $user->canInvestInPackage($package)) {
            return back()->with('error', 'User is not eligible for this package.');
        }

        if ($amount < $package->min_amount || $amount > $package->max_amount) {
            return back()->with('error', 'Amount is outside the package range.');
        }

        try {
            DB::beginTransaction();

            if (! $package->decrementSlot()) {
                DB::rollBack();
                return back()->with('error', 'No slots available for this package.');
            }

            $dailyInterest = $package->calculateDailyInterest($amount);
            $totalReturn = $package->calculateTotalReturn($amount);
            $startDate = now();
            $maturityDate = $startDate->copy()->addDays($package->duration_days);

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

            $user->transactions()->create([
                'type' => 'admin_package_activation',
                'amount' => $amount,
                'balance_before' => $user->balance,
                'balance_after' => $user->balance,
                'description' => 'Admin activated ' . $package->name . ' package',
                'reference' => 'TXN-' . strtoupper(Str::random(12)),
                'transactionable_type' => get_class($investment),
                'transactionable_id' => $investment->id,
            ]);

            event(new InvestmentCreated($investment));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Admin activation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to activate package. Please try again.');
        }

        return back()->with('success', 'Package activated for user. Receipt #' . $investment->receipt_number);
    }

    public function backfillInterest(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'user_id' => 'nullable|exists:users,id',
            'investment_id' => 'nullable|exists:investments,id',
            'dry_run' => 'nullable|boolean',
        ]);

        $from = Carbon::parse($request->input('from_date'))->startOfDay();
        $to = Carbon::parse($request->input('to_date'))->startOfDay();

        if ($from->gt($to)) {
            return back()->with('error', 'The start date must be before or equal to the end date.');
        }

        $maxDays = 31;
        $spanDays = $from->diffInDays($to) + 1;
        if ($spanDays > $maxDays) {
            return back()->with('error', "Date range too large. Limit is {$maxDays} days.");
        }

        $query = Investment::where('status', 'active')
            ->where('start_date', '<=', $to)
            ->where('maturity_date', '>', $from)
            ->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('investment_id')) {
            $query->where('id', $request->input('investment_id'));
        }

        $investments = $query->get();
        $totalApplied = 0;
        $totalSkipped = 0;
        $dryRun = $request->boolean('dry_run');

        foreach ($investments as $investment) {
            if (! $investment->user) {
                continue;
            }

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
                        "Daily interest from investment {$investment->receipt_number} (manual {$calcDate})",
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

        $mode = $dryRun ? 'Dry run' : 'Manual interest';

        return back()->with('success', "{$mode} complete. Applied: {$totalApplied}. Skipped: {$totalSkipped}.");
    }
}
