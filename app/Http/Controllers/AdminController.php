<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return view('admin.dashboard', compact('users', 'pendingDeposits', 'pendingWithdrawals'));
    }

    public function users()
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
}
