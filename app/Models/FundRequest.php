<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundRequest extends Model
{
    protected $fillable = [
        'requester_id',
        'requested_from_id',
        'amount',
        'message',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the requester
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get the user being requested from
     */
    public function requestedFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_from_id');
    }

    /**
     * Approve the request
     */
    public function approve(): void
    {
        // Deduct from requested user
        $this->requestedFrom->deductBalance(
            $this->amount,
            'fund_request',
            "Fund request to {$this->requester->name}",
            $this
        );

        // Add to requester
        $this->requester->addBalance(
            $this->amount,
            'fund_request',
            "Fund request from {$this->requestedFrom->name}",
            $this
        );

        $this->status = 'approved';
        $this->responded_at = now();
        $this->save();
    }

    /**
     * Reject the request
     */
    public function reject(): void
    {
        $this->status = 'rejected';
        $this->responded_at = now();
        $this->save();
    }

    /**
     * Cancel the request
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->responded_at = now();
        $this->save();
    }
}
