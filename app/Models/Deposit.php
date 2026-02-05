<?php

namespace App\Models;

use App\Events\InvestmentCreated;
use App\Models\Investment;
use App\Models\InvestmentPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Deposit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'proof_of_payment',
        'status',
        'approved_by',
        'approved_at',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who made the deposit
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get transactions
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Approve the deposit
     */
    public function approve(User $admin): void
    {
        DB::transaction(function () use ($admin) {
            $this->status = 'approved';
            $this->approved_by = $admin->id;
            $this->approved_at = now();
            $this->save();

            if (! $this->package_id) {
                $this->user->addBalance(
                    $this->amount,
                    'deposit',
                    "Deposit approved - {$this->payment_method}",
                    $this
                );
                return;
            }

            $package = InvestmentPackage::find($this->package_id);
            if (! $package) {
                throw new \Exception('Selected package not found.');
            }

            if ($this->amount < $package->min_amount || $this->amount > $package->max_amount) {
                throw new \Exception('Deposit amount is outside the package range.');
            }

            if (! $package->decrementSlot()) {
                throw new \Exception('No slots available for this package.');
            }

            $dailyInterest = $package->calculateDailyInterest((float) $this->amount);
            $totalReturn = $package->calculateTotalReturn((float) $this->amount);
            $startDate = now();
            $maturityDate = $startDate->copy()->addDays($package->duration_days);

            $investment = Investment::create([
                'user_id' => $this->user_id,
                'package_id' => $package->id,
                'amount' => $this->amount,
                'daily_interest' => $dailyInterest,
                'total_return' => $totalReturn,
                'total_earned' => 0,
                'duration_days' => $package->duration_days,
                'start_date' => $startDate,
                'maturity_date' => $maturityDate,
                'status' => 'active',
            ]);

            event(new InvestmentCreated($investment));
        });
    }

    /**
     * Reject the deposit
     */
    public function reject(User $admin, string $notes = null): void
    {
        $this->status = 'rejected';
        $this->approved_by = $admin->id;
        $this->approved_at = now();
        $this->admin_notes = $notes;
        $this->save();
    }
}
