<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentReceipt extends Model
{
    protected $fillable = [
        'investment_id',
        'user_id',
        'receipt_number',
        'receipt_data',
        'pdf_path',
        'generated_at',
    ];

    protected $casts = [
        'receipt_data' => 'array',
        'generated_at' => 'datetime',
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
