<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference',
        'transactionable_type',
        'transactionable_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * Get the user who owns the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transactionable model
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if transaction is credit
     */
    public function isCredit(): bool
    {
        return $this->balance_after > $this->balance_before;
    }

    /**
     * Check if transaction is debit
     */
    public function isDebit(): bool
    {
        return $this->balance_after < $this->balance_before;
    }
}
