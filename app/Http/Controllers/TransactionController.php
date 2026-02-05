<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->transactions()->latest();

        // Filter by type if provided
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date range if provided
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $netTotal = (clone $query)
            ->selectRaw('SUM(CASE WHEN balance_after > balance_before THEN amount ELSE -amount END) as net_total')
            ->value('net_total');

        $transactions = $query->paginate(20);

        return view('transactions.index', compact('transactions', 'netTotal'));
    }
}
