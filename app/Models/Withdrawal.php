<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdrawal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'amount',
        'withdrawal_method',
        'account_details',
        'status',
        'approved_by',
        'approved_at',
        'completed_at',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user who made the withdrawal
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
     * Approve the withdrawal
     */
    public function approve(User $admin): void
    {
        $alreadyDeducted = $this->transactions()
            ->where('type', 'withdrawal_request')
            ->exists();

        if (! $alreadyDeducted) {
            $this->user->deductBalance(
                $this->amount,
                'withdrawal_request',
                'Withdrawal request submitted',
                $this
            );
        }

        $this->status = 'approved';
        $this->approved_by = $admin->id;
        $this->approved_at = now();
        $this->save();
    }

    /**
     * Reject the withdrawal
     */
    public function reject(User $admin, string $notes = null): void
    {
        $alreadyDeducted = $this->transactions()
            ->where('type', 'withdrawal_request')
            ->exists();

        if ($alreadyDeducted) {
            $this->user->addBalance(
                $this->amount,
                'withdrawal_rejected',
                'Withdrawal rejected - funds returned',
                $this
            );
        }

        $this->status = 'rejected';
        $this->approved_by = $admin->id;
        $this->approved_at = now();
        $this->admin_notes = $notes;
        $this->save();
    }

    /**
     * Mark as processing
     */
    public function markProcessing(): void
    {
        $this->status = 'processing';
        $this->save();
    }

    /**
     * Mark as completed
     */
    public function complete(): void
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
    }
}
