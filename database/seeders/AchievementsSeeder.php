<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementsSeeder extends Seeder
{
    public function run()
    {
        $achievements = [
            [
                'name' => 'First Steps',
                'slug' => 'first-steps',
                'description' => 'Play your first game',
                'reward_gold' => 500,
                'icon' => '🎮',
                'category' => 'games',
                'unlock_criteria' => ['type' => 'games_played', 'count' => 1],
                'is_active' => true,
            ],
            [
                'name' => 'Game Enthusiast',
                'slug' => 'game-enthusiast',
                'description' => 'Play 100 games',
                'reward_gold' => 5000,
                'icon' => '🎯',
                'category' => 'games',
                'unlock_criteria' => ['type' => 'games_played', 'count' => 100],
                'is_active' => true,
            ],
            [
                'name' => 'Gaming Legend',
                'slug' => 'gaming-legend',
                'description' => 'Play 1000 games',
                'reward_gold' => 50000,
                'icon' => '👑',
                'category' => 'games',
                'unlock_criteria' => ['type' => 'games_played', 'count' => 1000],
                'is_active' => true,
            ],
            [
                'name' => 'First Good Deed',
                'slug' => 'first-good-deed',
                'description' => 'Make your first donation to any cause',
                'reward_gold' => 1000,
                'icon' => '💝',
                'category' => 'donations',
                'unlock_criteria' => ['type' => 'total_donations', 'count' => 0.01],
                'is_active' => true,
            ],
            [
                'name' => 'Generous Soul',
                'slug' => 'generous-soul',
                'description' => 'Donate $10 or more to charitable causes',
                'reward_gold' => 10000,
                'icon' => '🌟',
                'category' => 'donations',
                'unlock_criteria' => ['type' => 'total_donations', 'count' => 10],
                'is_active' => true,
            ],
            [
                'name' => 'Philanthropist',
                'slug' => 'philanthropist',
                'description' => 'Donate $100 or more to charitable causes',
                'reward_gold' => 100000,
                'icon' => '🏆',
                'category' => 'donations',
                'unlock_criteria' => ['type' => 'total_donations', 'count' => 100],
                'is_active' => true,
            ],
            [
                'name' => 'Diverse Supporter',
                'slug' => 'diverse-supporter',
                'description' => 'Support 5 different charitable causes',
                'reward_gold' => 5000,
                'icon' => '🌍',
                'category' => 'donations',
                'unlock_criteria' => ['type' => 'causes_supported', 'count' => 5],
                'is_active' => true,
            ],
            [
                'name' => 'Gold Collector',
                'slug' => 'gold-collector',
                'description' => 'Earn 100,000 Forum Gold total',
                'reward_gold' => 10000,
                'icon' => '💰',
                'category' => 'milestones',
                'unlock_criteria' => ['type' => 'gold_earned', 'count' => 100000],
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
