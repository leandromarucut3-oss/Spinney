<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use App\Events\InvestmentCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = auth()->user()->deposits()
            ->latest()
            ->paginate(15);

        return view('deposits.index', compact('deposits'));
    }

    public function create()
    {
        $packages = \App\Models\InvestmentPackage::where('is_active', true)
            ->orderBy('min_amount')
            ->get();

        return view('deposits.create', compact('packages'));
    }

    public function store(Request $request)
    {
        if ($request->filled('amount')) {
            $request->merge([
                'amount' => app(\App\Services\CurrencyService::class)
                    ->convertToBase((float) $request->input('amount')),
            ]);
        }

        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|string|max:255',
            'transaction_reference' => 'nullable|string|max:255',
            'proof_of_payment' => 'nullable|image|max:5120', // 5MB max
            'package_id' => 'nullable|exists:investment_packages,id',
        ]);

        $user = auth()->user();
        $data = $request->only(['amount', 'payment_method', 'transaction_reference', 'package_id']);
        $data['user_id'] = $user->id;
        $data['status'] = 'pending';

        if ($request->hasFile('proof_of_payment')) {
            $data['proof_of_payment'] = $request->file('proof_of_payment')->store('deposits', 'public');
        }

        if ($data['payment_method'] === 'account_balance') {
            $packageId = $request->input('package_id');

            if (!$packageId) {
                return back()->with('error', 'Please select a package to purchase.');
            }

            $package = InvestmentPackage::findOrFail($packageId);

            $request->validate([
                'amount' => [
                    'required',
                    'numeric',
                    'min:' . $package->min_amount,
                    'max:' . $package->max_amount,
                ],
            ]);

            if (!$user->canInvestInPackage($package)) {
                return back()->with('error', 'Your tier does not allow investment in this package.');
            }

            if ($user->balance < $data['amount']) {
                return back()->with('error', 'Insufficient balance to complete this purchase.');
            }

            DB::beginTransaction();
            try {
                if (!$package->decrementSlot()) {
                    DB::rollBack();
                    return back()->with('error', 'No slots available for this package.');
                }

                $dailyInterest = $package->calculateDailyInterest($data['amount']);
                $totalReturn = $package->calculateTotalReturn($data['amount']);
                $startDate = now();
                $maturityDate = $startDate->copy()->addDays($package->duration_days);

                $investment = Investment::create([
                    'user_id' => $user->id,
                    'package_id' => $package->id,
                    'amount' => $data['amount'],
                    'daily_interest' => $dailyInterest,
                    'total_return' => $totalReturn,
                    'total_earned' => 0,
                    'duration_days' => $package->duration_days,
                    'start_date' => $startDate,
                    'maturity_date' => $maturityDate,
                    'status' => 'active',
                ]);

                $user->deductBalance(
                    $data['amount'],
                    'investment',
                    'Investment in ' . $package->name,
                    $investment
                );

                event(new InvestmentCreated($investment));

                $data['status'] = 'approved';
                $data['approved_at'] = now();
                $data['approved_by'] = null;
                $data['admin_notes'] = 'Auto-approved using available balance.';

                Deposit::create($data);

                DB::commit();

                return redirect()->route('deposits.create')
                    ->with('success', 'Investment created successfully! Receipt #' . $investment->receipt_number);
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Balance purchase failed: ' . $e->getMessage());
                return back()->with('error', 'Failed to complete purchase. Please try again.');
            }
        }

        Deposit::create($data);

        return redirect()->route('deposits.index')
            ->with('success', 'Deposit request submitted successfully. Awaiting admin approval.');
    }
}
