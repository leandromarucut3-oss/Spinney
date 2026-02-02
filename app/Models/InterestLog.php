<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterestLog extends Model
{
    protected $fillable = [
        'investment_id',
        'user_id',
        'calculation_date',
        'interest_amount',
        'balance_before',
        'balance_after',
        'status',
    ];

    protected $casts = [
        'calculation_date' => 'date',
        'interest_amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * Get the investment
     */
    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
