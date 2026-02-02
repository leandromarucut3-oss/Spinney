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

                // Create referral record
                $referral = Referral::create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $user->id,
                    'level' => 1,
                    'signup_bonus' => $signupBonus,
                    'status' => 'active',
                ]);

                // Award signup bonus to referrer
                $referrer->addBalance(
                    $signupBonus,
                    'referral_signup',
                    "Signup bonus for referring {$user->name}",
                    $referral
                );

                // Process multi-level referrals (up to 3 levels)
                $this->processMultiLevelReferrals($user, $referrer);

                Log::info("Referral bonus processed: {$referrer->name} referred {$user->name}");
            });
        } catch (\Exception $e) {
            Log::error("Failed to process referral bonus: {$e->getMessage()}");
        }
    }

    /**
     * Process multi-level referral structure
     */
    private function processMultiLevelReferrals($newUser, $directReferrer): void
    {
        $currentReferrer = $directReferrer;
        $level = 2;
        $maxLevels = 3;

        while ($level <= $maxLevels && $currentReferrer->referred_by) {
            $currentReferrer = $currentReferrer->referrer;

            if ($currentReferrer) {
                Referral::create([
                    'referrer_id' => $currentReferrer->id,
                    'referred_id' => $newUser->id,
                    'level' => $level,
                    'signup_bonus' => 0,
                    'status' => 'active',
                ]);

                $level++;
            } else {
                break;
            }
        }
    }
}
