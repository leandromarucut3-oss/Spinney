<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
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
        $this->status = 'approved';
        $this->approved_by = $admin->id;
        $this->approved_at = now();
        $this->save();

        // Add balance to user
        $this->user->addBalance(
            $this->amount,
            'deposit',
            "Deposit approved - {$this->payment_method}",
            $this
        );
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
