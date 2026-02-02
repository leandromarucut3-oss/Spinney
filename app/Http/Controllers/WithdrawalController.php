<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;

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
        return view('withdrawals.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:' . auth()->user()->balance,
            'withdrawal_method' => 'required|string|max:255',
            'account_details' => 'required|string',
        ]);

        $user = auth()->user();

        // Check minimum balance
        if ($user->balance < $request->amount) {
            return back()->with('error', 'Insufficient balance.');
        }

        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'withdrawal_method' => $request->withdrawal_method,
            'account_details' => $request->account_details,
            'status' => 'pending',
        ]);

        return redirect()->route('withdrawals.index')
            ->with('success', 'Withdrawal request submitted successfully. Awaiting admin approval.');
    }
}
