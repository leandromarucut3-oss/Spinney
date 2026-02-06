<?php

namespace App\Listeners;

use App\Events\InvestmentCreated;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessReferralCommission
{
    /**
     * Handle the event.
     */
    public function handle(InvestmentCreated $event): void
    {
        $investment = $event->investment;
        $investor = $investment->user;

        try {
            DB::transaction(function () use ($investment, $investor) {
                // Get all referrals for this user
                $referrals = Referral::where('referred_id', $investor->id)
                    ->where('status', 'active')
                    ->where('level', 1)
                    ->with('referrer')
                    ->get();

                foreach ($referrals as $referral) {
                    $rate = 5.0; // Direct referral only
                    $commissionAmount = ($investment->amount * $rate) / 100;

                    $referral->addCommission(
                        $commissionAmount,
                        "Direct referral commission from {$investor->name}'s investment {$investment->receipt_number}"
                    );

                    Log::info("Referral commission: {$referral->referrer->name} earned {$commissionAmount} from direct referral");
                }
            });
        } catch (\Exception $e) {
            Log::error("Failed to process referral commission: {$e->getMessage()}");
        }
    }
}
