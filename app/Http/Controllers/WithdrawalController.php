<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = auth()->user()->withdrawals()
            ->latest()
            ->paginate(15);

        return view('withdrawals.index', compact('withdrawals'));
    }

    public function create()
    {
        $user = auth()->user();
        if (! $user->hasBankInfo()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Please add your bank information before requesting a withdrawal.');
        }
        if ($user->withdrawals()->where('status', 'pending')->exists()) {
            return redirect()->route('withdrawals.index')
                ->with('error', 'You already have a pending withdrawal request.');
        }
        return view('withdrawals.create', compact('user'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasBankInfo()) {
            return redirect()->route('profile.edit')
                ->with('error', 'Please add your bank information before requesting a withdrawal.');
        }
        if (auth()->user()->withdrawals()->where('status', 'pending')->exists()) {
            return back()->with('error', 'You already have a pending withdrawal request.');
        }

        if ($request->filled('amount')) {
            $request->merge([
                'amount' => app(\App\Services\CurrencyService::class)
                    ->convertToBase((float) $request->input('amount')),
            ]);
        }

        $request->validate([
            'amount' => 'required|numeric|min:75|max:' . auth()->user()->balance,
        ]);

        $user = auth()->user();

        // Check minimum balance
        if ($user->balance < $request->amount) {
            return back()->with('error', 'Insufficient balance.');
        }

        $withdrawal = DB::transaction(function () use ($user, $request) {
            $withdrawal = Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $request->amount,
                'withdrawal_method' => 'bank_transfer',
                'account_details' => $user->getBankAccountSummary(),
                'status' => 'pending',
            ]);

            $user->deductBalance(
                $request->amount,
                'withdrawal_request',
                'Withdrawal request submitted',
                $withdrawal
            );

            return $withdrawal;
        });

        return redirect()->route('withdrawals.show', $withdrawal)
            ->with('success', 'Withdrawal request submitted successfully. Awaiting admin approval.');
    }

    public function show(Withdrawal $withdrawal)
    {
        if ($withdrawal->user_id !== auth()->id()) {
            abort(403);
        }

        return view('withdrawals.show', compact('withdrawal'));
    }
}
