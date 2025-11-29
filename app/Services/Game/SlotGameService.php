<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;

class SlotGameService
{
    protected $symbols = ['🍒', '🍋', '🍊', '🍇', '💎', '⭐', '7️⃣', '🔔'];
    protected $payouts = [
        '7️⃣' => 100,  // Triple 7s
        '💎' => 50,    // Triple Diamonds
        '⭐' => 25,    // Triple Stars
        '🔔' => 15,    // Triple Bells
        '🍇' => 10,    // Triple Grapes
        '🍊' => 8,     // Triple Oranges
        '🍋' => 5,     // Triple Lemons
        '🍒' => 3,     // Triple Cherries
    ];

    public function play(User $user, Game $game, $betAmount, $clientSeed = null)
    {
        // Validate bet amount
        if ($betAmount < $game->min_bet || $betAmount > $game->max_bet) {
            throw new \Exception("Bet amount must be between {$game->min_bet} and {$game->max_bet}");
        }

        if ($user->forum_gold_balance < $betAmount) {
            throw new \Exception('Insufficient forum gold balance');
        }

        // Create game session
        $session = new GameSession();
        $session->user_id = $user->id;
        $session->game_id = $game->id;
        $session->bet_amount = $betAmount;
        $session->generateProvablyFairSeeds($clientSeed);

        // Generate slot result using provably fair
        $result = $this->generateResult($session->result_hash);

        // Calculate winnings
        $winAmount = $this->calculateWinnings($result, $betAmount);
        $profit = $winAmount - $betAmount;

        $session->win_amount = $winAmount;
        $session->profit = $profit;
        $session->is_win = $winAmount > 0;
        $session->game_data = [
            'reels' => $result,
            'symbols' => $this->symbols,
            'payout_table' => $this->payouts,
        ];
        $session->save();

        // Update user balance
        if ($profit > 0) {
            $user->addForumGold($profit, 'game_win', "Won {$profit} Forum Gold playing Slots", $session->id, 'GameSession');
        } elseif ($profit < 0) {
            $user->subtractForumGold(abs($profit), 'game_loss', "Lost " . abs($profit) . " Forum Gold playing Slots", $session->id, 'GameSession');
        }

        // Add XP
        $user->addXp(10);

        // Update game statistics
        $game->recordPlay($betAmount, $winAmount);

        return [
            'session' => $session,
            'result' => $result,
            'win_amount' => $winAmount,
            'profit' => $profit,
            'balance' => $user->fresh()->forum_gold_balance,
        ];
    }

    protected function generateResult($hash)
    {
        $reels = [];
        for ($i = 0; $i < 3; $i++) {
            // Use hash to generate deterministic random result
            $segment = substr($hash, $i * 8, 8);
            $index = hexdec($segment) % count($this->symbols);
            $reels[] = $this->symbols[$index];
        }
        return $reels;
    }

    protected function calculateWinnings($result, $betAmount)
    {
        // Check if all three match
        if ($result[0] === $result[1] && $result[1] === $result[2]) {
            $symbol = $result[0];
            $multiplier = $this->payouts[$symbol] ?? 2;
            return $betAmount * $multiplier;
        }

        // Check if two match (smaller payout)
        if ($result[0] === $result[1] || $result[1] === $result[2] || $result[0] === $result[2]) {
            return $betAmount * 0.5; // Return half the bet
        }

        return 0;
    }
}
