<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\Request;
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
            ->orderBy('minimum_investment')
            ->get();

        return view('deposits.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|string|max:255',
            'transaction_reference' => 'nullable|string|max:255',
            'proof_of_payment' => 'nullable|image|max:5120', // 5MB max
        ]);

        $data = $request->only(['amount', 'payment_method', 'transaction_reference']);
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        if ($request->hasFile('proof_of_payment')) {
            $data['proof_of_payment'] = $request->file('proof_of_payment')->store('deposits', 'public');
        }

        Deposit::create($data);

        return redirect()->route('deposits.index')
            ->with('success', 'Deposit request submitted successfully. Awaiting admin approval.');
    }
}
