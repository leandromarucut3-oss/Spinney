<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Raffle extends Model
{
    protected $fillable = [
        'title',
        'description',
        'prize_amount',
        'total_entries',
        'draw_date',
        'winner_id',
        'status',
        'drawn_at',
    ];

    protected $casts = [
        'prize_amount' => 'decimal:2',
        'draw_date' => 'date',
        'drawn_at' => 'datetime',
    ];

    /**
     * Get the winner
     */
    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    /**
     * Get raffle entries
     */
    public function entries(): HasMany
    {
        return $this->hasMany(RaffleEntry::class);
    }

    /**
     * Draw a winner
     */
    public function drawWinner(): ?User
    {
        $entries = $this->entries()->with('user')->get();

        if ($entries->isEmpty()) {
            return null;
        }

        // Weight entries by number of tickets
        $pool = [];
        foreach ($entries as $entry) {
            for ($i = 0; $i < $entry->entries; $i++) {
                $pool[] = $entry->user_id;
            }
        }

        $winnerId = $pool[array_rand($pool)];
        $winner = User::find($winnerId);

        $this->winner_id = $winnerId;
        $this->status = 'drawn';
        $this->drawn_at = now();
        $this->save();

        // Award prize
        $winner->addBalance(
            $this->prize_amount,
            'raffle_prize',
            "Won raffle: {$this->title}",
            $this
        );

        return $winner;
    }
}
