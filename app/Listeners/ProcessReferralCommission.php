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
                    ->with('referrer')
                    ->get();

                // Commission rates by level
                $commissionRates = [
                    1 => 5.0,  // 5% for direct referrals
                    2 => 2.0,  // 2% for level 2
                    3 => 1.0,  // 1% for level 3
                ];

                foreach ($referrals as $referral) {
                    $rate = $commissionRates[$referral->level] ?? 0;

                    if ($rate > 0) {
                        $commissionAmount = ($investment->amount * $rate) / 100;

                        $referral->addCommission(
                            $commissionAmount,
                            "Level {$referral->level} commission from {$investor->name}'s investment {$investment->receipt_number}"
                        );

                        Log::info("Referral commission: {$referral->referrer->name} earned {$commissionAmount} from level {$referral->level}");
                    }
                }
            });
        } catch (\Exception $e) {
            Log::error("Failed to process referral commission: {$e->getMessage()}");
        }
    }
}
