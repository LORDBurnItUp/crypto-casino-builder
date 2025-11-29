<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;

class DiceGameService
{
    public function play(User $user, Game $game, $betAmount, $prediction, $targetNumber, $clientSeed = null)
    {
        // Validate inputs
        if ($betAmount < $game->min_bet || $betAmount > $game->max_bet) {
            throw new \Exception("Bet amount must be between {$game->min_bet} and {$game->max_bet}");
        }

        if ($user->forum_gold_balance < $betAmount) {
            throw new \Exception('Insufficient forum gold balance');
        }

        if (!in_array($prediction, ['over', 'under'])) {
            throw new \Exception('Prediction must be "over" or "under"');
        }

        if ($targetNumber < 2 || $targetNumber > 98) {
            throw new \Exception('Target number must be between 2 and 98');
        }

        // Create game session
        $session = new GameSession();
        $session->user_id = $user->id;
        $session->game_id = $game->id;
        $session->bet_amount = $betAmount;
        $session->generateProvablyFairSeeds($clientSeed);

        // Generate dice roll (1-100)
        $roll = $this->generateRoll($session->result_hash);

        // Check win condition
        $isWin = false;
        if ($prediction === 'over' && $roll > $targetNumber) {
            $isWin = true;
        } elseif ($prediction === 'under' && $roll < $targetNumber) {
            $isWin = true;
        }

        // Calculate winnings based on probability
        $winAmount = 0;
        if ($isWin) {
            $multiplier = $this->calculateMultiplier($prediction, $targetNumber);
            $winAmount = $betAmount * $multiplier;
        }

        $profit = $winAmount - $betAmount;

        $session->win_amount = $winAmount;
        $session->profit = $profit;
        $session->is_win = $isWin;
        $session->game_data = [
            'roll' => $roll,
            'prediction' => $prediction,
            'target_number' => $targetNumber,
            'multiplier' => $isWin ? $multiplier : 0,
        ];
        $session->save();

        // Update user balance
        if ($profit > 0) {
            $user->addForumGold($profit, 'game_win', "Won {$profit} Forum Gold playing Dice", $session->id, 'GameSession');
        } elseif ($profit < 0) {
            $user->subtractForumGold(abs($profit), 'game_loss', "Lost " . abs($profit) . " Forum Gold playing Dice", $session->id, 'GameSession');
        }

        // Add XP
        $user->addXp(10);

        // Update game statistics
        $game->recordPlay($betAmount, $winAmount);

        return [
            'session' => $session,
            'roll' => $roll,
            'is_win' => $isWin,
            'win_amount' => $winAmount,
            'profit' => $profit,
            'multiplier' => $isWin ? $multiplier : 0,
            'balance' => $user->fresh()->forum_gold_balance,
        ];
    }

    protected function generateRoll($hash)
    {
        $segment = substr($hash, 0, 8);
        return (hexdec($segment) % 100) + 1; // 1-100
    }

    protected function calculateMultiplier($prediction, $targetNumber)
    {
        // Calculate win probability
        if ($prediction === 'over') {
            $winChance = (100 - $targetNumber) / 100;
        } else {
            $winChance = ($targetNumber - 1) / 100;
        }

        // Multiplier = (1 / win_chance) * (1 - house_edge)
        // House edge of 2%
        $houseEdge = 0.02;
        $multiplier = (1 / $winChance) * (1 - $houseEdge);

        return round($multiplier, 2);
    }
}
