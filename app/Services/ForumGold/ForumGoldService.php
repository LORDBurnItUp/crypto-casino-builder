<?php

namespace App\Services\ForumGold;

use App\Models\User;
use App\Models\DailyBonus;
use Illuminate\Support\Facades\DB;

class ForumGoldService
{
    public function processDailyBonus(User $user)
    {
        $today = today();

        // Check if already claimed today
        $alreadyClaimed = DailyBonus::where('user_id', $user->id)
            ->where('login_date', $today)
            ->exists();

        if ($alreadyClaimed) {
            return [
                'success' => false,
                'message' => 'Daily bonus already claimed today',
            ];
        }

        // Calculate streak
        $yesterday = today()->subDay();
        $yesterdayBonus = DailyBonus::where('user_id', $user->id)
            ->where('login_date', $yesterday)
            ->first();

        $streakDays = $yesterdayBonus ? $yesterdayBonus->streak_days + 1 : 1;

        // Calculate bonus amount
        $bonusAmount = DailyBonus::calculateBonusAmount($streakDays);

        // Create bonus record
        $bonus = DailyBonus::create([
            'user_id' => $user->id,
            'login_date' => $today,
            'bonus_amount' => $bonusAmount,
            'streak_days' => $streakDays,
        ]);

        // Add gold to user
        $user->addForumGold(
            $bonusAmount,
            'bonus',
            "Daily login bonus - {$streakDays} day streak!",
            $bonus->id,
            'DailyBonus'
        );

        // Update user streak
        $user->current_streak = $streakDays;
        $user->last_login_date = $today;
        $user->save();

        return [
            'success' => true,
            'bonus_amount' => $bonusAmount,
            'streak_days' => $streakDays,
            'new_balance' => $user->fresh()->forum_gold_balance,
        ];
    }

    public function getTransactionHistory(User $user, $limit = 50, $offset = 0)
    {
        return $user->forumGoldTransactions()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    public function getUserStats(User $user)
    {
        $transactions = $user->forumGoldTransactions();

        return [
            'current_balance' => $user->forum_gold_balance,
            'total_earned' => $transactions->where('amount', '>', 0)->sum('amount'),
            'total_spent' => abs($transactions->where('amount', '<', 0)->sum('amount')),
            'total_from_games' => $transactions->whereIn('type', ['game_win'])->sum('amount'),
            'total_from_bonuses' => $transactions->where('type', 'bonus')->sum('amount'),
            'total_donated' => abs($transactions->where('type', 'donation')->sum('amount')),
            'current_streak' => $user->current_streak,
            'level' => $user->level,
            'xp' => $user->xp,
        ];
    }

    public function transferGold(User $from, User $to, $amount, $description = null)
    {
        if ($amount <= 0) {
            throw new \Exception('Transfer amount must be positive');
        }

        if ($from->forum_gold_balance < $amount) {
            throw new \Exception('Insufficient forum gold balance');
        }

        DB::transaction(function () use ($from, $to, $amount, $description) {
            $desc = $description ?? "Transfer to {$to->username}";

            // Deduct from sender
            $from->subtractForumGold($amount, 'spend', $desc);

            // Add to receiver
            $to->addForumGold($amount, 'earn', "Transfer from {$from->username}");
        });

        return [
            'success' => true,
            'from_balance' => $from->fresh()->forum_gold_balance,
            'to_balance' => $to->fresh()->forum_gold_balance,
        ];
    }
}
