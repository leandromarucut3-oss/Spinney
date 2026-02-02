<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'level',
        'signup_bonus',
        'total_commission',
        'status',
    ];

    protected $casts = [
        'signup_bonus' => 'decimal:2',
        'total_commission' => 'decimal:2',
    ];

    /**
     * Get the referrer user
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the referred user
     */
    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Add commission
     */
    public function addCommission(float $amount, string $description): void
    {
        $this->total_commission += $amount;
        $this->save();

        // Add balance to referrer
        $this->referrer->addBalance(
            $amount,
            'referral_commission',
            $description,
            $this
        );
    }

    /**
     * Check if referral is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
