<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StockDataController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Stock data API endpoint
Route::get('/api/stock-data', [StockDataController::class, 'getStockData'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Investments
    Route::get('/investments/packages', [InvestmentController::class, 'packages'])->name('investments.packages');
    Route::get('/investments/{package}', [InvestmentController::class, 'show'])->name('investments.show');
    Route::post('/investments/{package}/invest', [InvestmentController::class, 'invest'])->name('investments.invest');
    Route::get('/investments', [InvestmentController::class, 'index'])->name('investments.index');

    // Deposits
    Route::get('/deposits', [DepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/create', [DepositController::class, 'create'])->name('deposits.create');
    Route::post('/deposits', [DepositController::class, 'store'])->name('deposits.store');

    // Withdrawals
    Route::get('/withdrawals', [WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [WithdrawalController::class, 'store'])->name('withdrawals.store');

    // Referrals
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
});

require __DIR__.'/auth.php';
