<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RaffleEntry extends Model
{
    protected $fillable = [
        'raffle_id',
        'user_id',
        'entries',
    ];

    protected $casts = [
        'entries' => 'integer',
    ];

    /**
     * Get the raffle
     */
    public function raffle(): BelongsTo
    {
        return $this->belongsTo(Raffle::class);
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
