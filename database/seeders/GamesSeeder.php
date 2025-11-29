<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;

class GamesSeeder extends Seeder
{
    public function run()
    {
        $games = [
            [
                'name' => 'Slots',
                'slug' => 'slots',
                'type' => 'slots',
                'description' => 'Classic 3-reel slot machine. Match symbols to win big!',
                'min_bet' => 10,
                'max_bet' => 10000,
                'house_edge' => 2.5,
                'icon' => '🎰',
                'is_active' => true,
                'game_config' => [
                    'reels' => 3,
                    'symbols' => ['🍒', '🍋', '🍊', '🍇', '💎', '⭐', '7️⃣', '🔔'],
                ],
            ],
            [
                'name' => 'Roulette',
                'slug' => 'roulette',
                'type' => 'roulette',
                'description' => 'European roulette with numbers 0-36. Bet on colors, numbers, or ranges!',
                'min_bet' => 10,
                'max_bet' => 5000,
                'house_edge' => 2.7,
                'icon' => '🎯',
                'is_active' => true,
                'game_config' => [
                    'numbers' => 37,
                    'bet_types' => ['number', 'color', 'even_odd', 'high_low', 'dozen'],
                ],
            ],
            [
                'name' => 'Dice',
                'slug' => 'dice',
                'type' => 'dice',
                'description' => 'Roll the dice! Predict if the result will be over or under your target.',
                'min_bet' => 10,
                'max_bet' => 15000,
                'house_edge' => 2.0,
                'icon' => '🎲',
                'is_active' => true,
                'game_config' => [
                    'min_number' => 1,
                    'max_number' => 100,
                ],
            ],
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}
