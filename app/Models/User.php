<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'pin',
        'phone',
        'balance',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_iban',
        'bank_swift_code',
        'referral_code',
        'referred_by',
        'tier',
        'is_verified',
        'is_suspended',
        'is_admin',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'pin',
        'remember_token',
        'email_verification_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'is_verified' => 'boolean',
            'is_suspended' => 'boolean',
            'is_admin' => 'boolean',
            'last_login_at' => 'datetime',
            'email_verification_token_expires_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = $user->username;
            }
        });
    }

    /**
     * Hash PIN before saving
     */
    public function setPinAttribute($value): void
    {
        if ($value) {
            $this->attributes['pin'] = bcrypt($value);
        }
    }

    /**
     * Normalize username before saving
     */
    public function setUsernameAttribute($value): void
    {
        $this->attributes['username'] = $value ? Str::lower(trim((string) $value)) : null;
    }

    /**
     * Check if PIN matches
     */
    public function checkPin(string $pin): bool
    {
        return $this->pin && password_verify($pin, $this->pin);
    }

    /**
     * Get user's investments
     */
    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    /**
     * Get user's active investments
     */
    public function activeInvestments(): HasMany
    {
        return $this->investments()->where('status', 'active');
    }

    /**
     * Get user's deposits
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    /**
     * Get user's withdrawals
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * Get user's transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get referrals made by this user
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Get user who referred this user
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Get users referred by this user
     */
    public function referredUsers(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    /**
     * Get user's achievements
     */
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withTimestamps()
            ->withPivot('achieved_at');
    }

    /**
     * Get user's attendance records
     */
    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get user's raffle entries
     */
    public function raffleEntries(): HasMany
    {
        return $this->hasMany(RaffleEntry::class);
    }

    /**
     * Get user's interest logs
     */
    public function interestLogs(): HasMany
    {
        return $this->hasMany(InterestLog::class);
    }

    /**
     * Get fund requests made by user
     */
    public function fundRequestsMade(): HasMany
    {
        return $this->hasMany(FundRequest::class, 'requester_id');
    }

    /**
     * Get fund requests received
     */
    public function fundRequestsReceived(): HasMany
    {
        return $this->hasMany(FundRequest::class, 'requested_from_id');
    }

    /**
     * Get user's audit logs
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Add balance with transaction logging
     */
    public function addBalance(float $amount, string $type, string $description, $transactionable = null): Transaction
    {
        $balanceBefore = $this->balance;
        $this->balance += $amount;
        $this->save();

        return $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference' => 'TXN-' . strtoupper(Str::random(12)),
            'transactionable_type' => $transactionable ? get_class($transactionable) : null,
            'transactionable_id' => $transactionable?->id,
        ]);
    }

    /**
     * Deduct balance with transaction logging
     */
    public function deductBalance(float $amount, string $type, string $description, $transactionable = null): Transaction
    {
        if ($this->balance < $amount) {
            throw new \Exception('Insufficient balance');
        }

        $balanceBefore = $this->balance;
        $this->balance -= $amount;
        $this->save();

        return $this->transactions()->create([
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference' => 'TXN-' . strtoupper(Str::random(12)),
            'transactionable_type' => $transactionable ? get_class($transactionable) : null,
            'transactionable_id' => $transactionable?->id,
        ]);
    }

    /**
     * Generate email verification token
     */
    public function generateEmailVerificationToken(): string
    {
        $token = Str::random(64);
        $this->email_verification_token = hash('sha256', $token);
        $this->email_verification_token_expires_at = now()->addHours(24);
        $this->save();

        return $token;
    }

    /**
     * Verify email with token
     */
    public function verifyEmailWithToken(string $token): bool
    {
        if ($this->email_verification_token !== hash('sha256', $token)) {
            return false;
        }

        if ($this->email_verification_token_expires_at < now()) {
            return false;
        }

        $this->email_verified_at = now();
        $this->email_verification_token = null;
        $this->email_verification_token_expires_at = null;
        $this->save();

        return true;
    }

    /**
     * Check if user can invest in package
     */
    public function canInvestInPackage(InvestmentPackage $package): bool
    {
        if ($this->is_suspended) {
            return false;
        }

        return true;
    }

    /**
     * Check if user has bank details on file
     */
    public function hasBankInfo(): bool
    {
        return !empty($this->bank_name)
            && !empty($this->bank_account_name)
            && !empty($this->bank_account_number);
    }

    /**
     * Build a bank account summary for withdrawal records
     */
    public function getBankAccountSummary(): string
    {
        $parts = [
            'Bank: ' . $this->bank_name,
            'Account Name: ' . $this->bank_account_name,
            'Account Number: ' . $this->bank_account_number,
        ];

        if (!empty($this->bank_iban)) {
            $parts[] = 'IBAN: ' . $this->bank_iban;
        }

        if (!empty($this->bank_swift_code)) {
            $parts[] = 'SWIFT: ' . $this->bank_swift_code;
        }

        return implode(' | ', $parts);
    }
}

