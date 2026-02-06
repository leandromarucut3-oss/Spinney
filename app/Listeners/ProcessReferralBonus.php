<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessReferralBonus
{
    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $user = $event->user;

        if (!$user->referred_by) {
            return;
        }

        try {
            DB::transaction(function () use ($user) {
                $referrer = $user->referrer;
                if (!$referrer) {
                    return;
                }

                // Signup bonus amount (configurable)
                $signupBonus = 10.00;

                // Direct signup only (no multi-level)
                $referral = Referral::firstOrCreate(
                    [
                        'referrer_id' => $referrer->id,
                        'referred_id' => $user->id,
                        'level' => 1,
                    ],
                    [
                        'signup_bonus' => $signupBonus,
                        'status' => 'active',
                    ]
                );

                if ($referral->wasRecentlyCreated) {
                    // Award signup bonus to referrer
                    $referrer->addBalance(
                        $signupBonus,
                        'referral_signup',
                        "Signup bonus for referring {$user->name}",
                        $referral
                    );
                }

                Log::info("Referral bonus processed: {$referrer->name} referred {$user->name}");
            });
        } catch (\Exception $e) {
            Log::error("Failed to process referral bonus: {$e->getMessage()}");
        }
    }
}
