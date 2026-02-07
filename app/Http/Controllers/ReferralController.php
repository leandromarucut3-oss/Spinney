<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $referrals = $user->referrals()
            ->with('referred')
            ->latest()
            ->get();

        $referredUsers = $user->referredUsers()
            ->withCount('investments')
            ->withSum(['investments as active_investments_amount' => function ($query) {
                $query->where('status', 'active');
            }], 'amount')
            ->get();

        $totalCommission = $referrals->sum('total_commission');
        $totalReferrals = $referredUsers->count();

        return view('referrals.index', compact(
            'user',
            'referrals',
            'referredUsers',
            'totalCommission',
            'totalReferrals'
        ));
    }
}
