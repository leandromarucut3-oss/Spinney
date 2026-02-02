<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_date',
        'streak',
        'bonus',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'bonus' => 'decimal:2',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate streak bonus
     */
    public static function calculateStreakBonus(int $streak): float
    {
        return match(true) {
            $streak >= 30 => 50.00,
            $streak >= 14 => 25.00,
            $streak >= 7 => 10.00,
            $streak >= 3 => 5.00,
            default => 1.00,
        };
    }
}
