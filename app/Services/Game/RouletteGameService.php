<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\User;

class RouletteGameService
{
    protected $numbers = [
        0 => 'green',
        1 => 'red', 2 => 'black', 3 => 'red', 4 => 'black', 5 => 'red',
        6 => 'black', 7 => 'red', 8 => 'black', 9 => 'red', 10 => 'black',
        11 => 'black', 12 => 'red', 13 => 'black', 14 => 'red', 15 => 'black',
        16 => 'red', 17 => 'black', 18 => 'red', 19 => 'red', 20 => 'black',
        21 => 'red', 22 => 'black', 23 => 'red', 24 => 'black', 25 => 'red',
        26 => 'black', 27 => 'red', 28 => 'black', 29 => 'black', 30 => 'red',
        31 => 'black', 32 => 'red', 33 => 'black', 34 => 'red', 35 => 'black', 36 => 'red'
    ];

    public function play(User $user, Game $game, $betAmount, $betType, $betValue, $clientSeed = null)
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

        // Generate result
        $number = $this->generateNumber($session->result_hash);
        $color = $this->numbers[$number];

        // Calculate winnings
        $winAmount = $this->calculateWinnings($number, $color, $betType, $betValue, $betAmount);
        $profit = $winAmount - $betAmount;

        $session->win_amount = $winAmount;
        $session->profit = $profit;
        $session->is_win = $winAmount > 0;
        $session->game_data = [
            'number' => $number,
            'color' => $color,
            'bet_type' => $betType,
            'bet_value' => $betValue,
        ];
        $session->save();

        // Update user balance
        if ($profit > 0) {
            $user->addForumGold($profit, 'game_win', "Won {$profit} Forum Gold playing Roulette", $session->id, 'GameSession');
        } elseif ($profit < 0) {
            $user->subtractForumGold(abs($profit), 'game_loss', "Lost " . abs($profit) . " Forum Gold playing Roulette", $session->id, 'GameSession');
        }

        // Add XP
        $user->addXp(10);

        // Update game statistics
        $game->recordPlay($betAmount, $winAmount);

        return [
            'session' => $session,
            'number' => $number,
            'color' => $color,
            'win_amount' => $winAmount,
            'profit' => $profit,
            'balance' => $user->fresh()->forum_gold_balance,
        ];
    }

    protected function generateNumber($hash)
    {
        $segment = substr($hash, 0, 8);
        return hexdec($segment) % 37; // 0-36
    }

    protected function calculateWinnings($number, $color, $betType, $betValue, $betAmount)
    {
        switch ($betType) {
            case 'number':
                return ($number == $betValue) ? $betAmount * 35 : 0;

            case 'color':
                return ($color == $betValue) ? $betAmount * 2 : 0;

            case 'even_odd':
                if ($number == 0) return 0;
                $isEven = ($number % 2 == 0);
                $won = ($betValue == 'even' && $isEven) || ($betValue == 'odd' && !$isEven);
                return $won ? $betAmount * 2 : 0;

            case 'high_low':
                if ($number == 0) return 0;
                $isHigh = $number >= 19;
                $won = ($betValue == 'high' && $isHigh) || ($betValue == 'low' && !$isHigh);
                return $won ? $betAmount * 2 : 0;

            case 'dozen':
                if ($number == 0) return 0;
                $dozen = ceil($number / 12);
                return ($dozen == $betValue) ? $betAmount * 3 : 0;

            default:
                return 0;
        }
    }
}
