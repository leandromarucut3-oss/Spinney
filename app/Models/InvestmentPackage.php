<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class InvestmentPackage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'min_amount',
        'max_amount',
        'daily_interest_rate',
        'duration_days',
        'total_slots',
        'available_slots',
        'is_active',
        'tier_required',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'daily_interest_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get investments for this package
     */
    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class, 'package_id');
    }

    /**
     * Get active investments
     */
    public function activeInvestments(): HasMany
    {
        return $this->investments()->where('status', 'active');
    }

    /**
     * Atomically decrement available slots
     */
    public function decrementSlot(): bool
    {
        return DB::table('investment_packages')
            ->where('id', $this->id)
            ->where('available_slots', '>', 0)
            ->update([
                'available_slots' => DB::raw('available_slots - 1'),
                'updated_at' => now(),
            ]) > 0;
    }

    /**
     * Atomically increment available slots
     */
    public function incrementSlot(): bool
    {
        return DB::table('investment_packages')
            ->where('id', $this->id)
            ->where('available_slots', '<', DB::raw('total_slots'))
            ->update([
                'available_slots' => DB::raw('available_slots + 1'),
                'updated_at' => now(),
            ]) > 0;
    }

    /**
     * Check if package has available slots
     */
    public function hasAvailableSlots(): bool
    {
        return $this->available_slots > 0;
    }

    /**
     * Calculate daily interest for amount
     */
    public function calculateDailyInterest(float $amount): float
    {
        return round($amount * ($this->daily_interest_rate / 100), 2);
    }

    /**
     * Calculate total return for amount
     */
    public function calculateTotalReturn(float $amount): float
    {
        $dailyInterest = $this->calculateDailyInterest($amount);
        return round($amount + ($dailyInterest * $this->duration_days), 2);
    }
}
