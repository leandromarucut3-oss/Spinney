<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'points',
        'requirements',
        'is_active',
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get users who have this achievement
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withTimestamps()
            ->withPivot('achieved_at');
    }

    /**
     * Award achievement to user
     */
    public function awardTo(User $user): void
    {
        if (!$this->users()->where('user_id', $user->id)->exists()) {
            $this->users()->attach($user->id, ['achieved_at' => now()]);
        }
    }
}
