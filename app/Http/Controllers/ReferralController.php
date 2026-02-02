<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $referrals = $user->referrals()
            ->with('referredUser')
            ->latest()
            ->get();

        $referredUsers = $user->referredUsers()
            ->withCount('investments')
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
