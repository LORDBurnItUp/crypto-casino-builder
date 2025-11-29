<?php

namespace App\Services\Donation;

use App\Models\User;
use App\Models\CharitableCause;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;

class DonationService
{
    // Conversion rate: Forum Gold to USD
    // Example: 10,000 Forum Gold = $1 USD
    protected $conversionRate = 10000;

    public function createDonation(User $user, CharitableCause $cause, $forumGoldAmount)
    {
        if (!$cause->is_active) {
            throw new \Exception('This charitable cause is not currently accepting donations');
        }

        if ($user->forum_gold_balance < $forumGoldAmount) {
            throw new \Exception('Insufficient forum gold balance');
        }

        if ($forumGoldAmount < 1000) {
            throw new \Exception('Minimum donation is 1,000 Forum Gold');
        }

        // Calculate real money equivalent
        $realMoneyAmount = $forumGoldAmount / $this->conversionRate;

        return DB::transaction(function () use ($user, $cause, $forumGoldAmount, $realMoneyAmount) {
            // Deduct forum gold from user
            $user->subtractForumGold(
                $forumGoldAmount,
                'donation',
                "Donated to {$cause->name}",
                null,
                'Donation'
            );

            // Create donation record
            $donation = Donation::create([
                'user_id' => $user->id,
                'cause_id' => $cause->id,
                'forum_gold_amount' => $forumGoldAmount,
                'real_money_amount' => $realMoneyAmount,
                'status' => 'pending',
                'certificate_message' => $this->generateCertificateMessage($user, $cause, $realMoneyAmount),
            ]);

            // Update transaction with donation reference
            $transaction = $user->forumGoldTransactions()
                ->where('type', 'donation')
                ->latest()
                ->first();

            if ($transaction) {
                $transaction->reference_id = $donation->id;
                $transaction->save();
            }

            return $donation;
        });
    }

    public function processDonation(Donation $donation, $transactionReference = null)
    {
        if ($donation->status !== 'pending') {
            throw new \Exception('Donation is not in pending status');
        }

        $donation->markAsCompleted($transactionReference);

        return [
            'success' => true,
            'donation' => $donation,
            'cause' => $donation->cause,
        ];
    }

    public function getUserDonationStats(User $user)
    {
        $donations = $user->donations()->where('status', 'completed');

        return [
            'total_donated_gold' => $user->donations()->sum('forum_gold_amount'),
            'total_donated_usd' => $donations->sum('real_money_amount'),
            'total_donations' => $donations->count(),
            'causes_supported' => $donations->distinct('cause_id')->count('cause_id'),
            'recent_donations' => $donations->with('cause')->latest()->limit(10)->get(),
        ];
    }

    public function getGlobalImpact()
    {
        return [
            'total_donations' => Donation::where('status', 'completed')->sum('real_money_amount'),
            'total_donors' => Donation::where('status', 'completed')->distinct('user_id')->count('user_id'),
            'total_causes_supported' => CharitableCause::where('total_donations_received', '>', 0)->count(),
            'top_causes' => CharitableCause::orderBy('total_donations_received', 'desc')->limit(5)->get(),
        ];
    }

    protected function generateCertificateMessage(User $user, CharitableCause $cause, $amount)
    {
        $formatted = number_format($amount, 2);
        return "This certificate confirms that {$user->username} has contributed \${$formatted} to {$cause->name}, making a positive impact on {$cause->category}.";
    }

    public function getCausesByCategory($category = null)
    {
        $query = CharitableCause::where('is_active', true);

        if ($category) {
            $query->where('category', $category);
        }

        return $query->orderBy('total_donations_received', 'desc')->get();
    }

    public function getConversionRate()
    {
        return $this->conversionRate;
    }

    public function setConversionRate($rate)
    {
        $this->conversionRate = $rate;
    }
}
