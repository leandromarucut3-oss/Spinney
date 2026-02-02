<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Investment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'daily_interest',
        'total_return',
        'total_earned',
        'duration_days',
        'start_date',
        'maturity_date',
        'status',
        'receipt_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'daily_interest' => 'decimal:2',
        'total_return' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'start_date' => 'date',
        'maturity_date' => 'date',
    ];

    /**
     * Boot the model
     */
    protected static function booted(): void
    {
        static::creating(function ($investment) {
            if (empty($investment->receipt_number)) {
                $investment->receipt_number = 'INV-' . strtoupper(Str::random(12));
            }
        });
    }

    /**
     * Get the user who owns the investment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the investment package
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(InvestmentPackage::class, 'package_id');
    }

    /**
     * Get interest logs
     */
    public function interestLogs(): HasMany
    {
        return $this->hasMany(InterestLog::class);
    }

    /**
     * Get receipts
     */
    public function receipts(): HasMany
    {
        return $this->hasMany(InvestmentReceipt::class);
    }

    /**
     * Get transactions
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Check if investment is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if investment has matured
     */
    public function hasMatured(): bool
    {
        return $this->maturity_date <= now()->toDateString();
    }

    /**
     * Complete the investment
     */
    public function complete(): void
    {
        $this->status = 'completed';
        $this->save();

        // Return principal + earnings to user
        $totalAmount = $this->amount + $this->total_earned;
        $this->user->addBalance(
            $totalAmount,
            'investment_maturity',
            "Investment {$this->receipt_number} matured",
            $this
        );
    }

    /**
     * Cancel the investment
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->save();

        // Return principal to user
        $this->user->addBalance(
            $this->amount,
            'investment_cancelled',
            "Investment {$this->receipt_number} cancelled",
            $this
        );

        // Return slot to package
        $this->package->incrementSlot();
    }
}
