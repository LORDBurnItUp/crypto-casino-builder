<?php

namespace App\Services\Achievement;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Support\Facades\DB;

class AchievementService
{
    public function checkAndUnlockAchievements(User $user)
    {
        $achievements = Achievement::where('is_active', true)->get();
        $unlocked = [];

        foreach ($achievements as $achievement) {
            if ($achievement->checkUnlock($user)) {
                $achievement->unlockForUser($user);
                $unlocked[] = $achievement;
            }
        }

        return $unlocked;
    }

    public function claimAchievementReward(User $user, Achievement $achievement)
    {
        $userAchievement = DB::table('user_achievements')
            ->where('user_id', $user->id)
            ->where('achievement_id', $achievement->id)
            ->first();

        if (!$userAchievement) {
            throw new \Exception('Achievement not unlocked');
        }

        if ($userAchievement->reward_claimed) {
            throw new \Exception('Reward already claimed');
        }

        // Give reward
        $user->addForumGold(
            $achievement->reward_gold,
            'achievement',
            "Achievement unlocked: {$achievement->name}",
            $achievement->id,
            'Achievement'
        );

        // Mark as claimed
        DB::table('user_achievements')
            ->where('user_id', $user->id)
            ->where('achievement_id', $achievement->id)
            ->update(['reward_claimed' => true]);

        return [
            'success' => true,
            'achievement' => $achievement,
            'reward_amount' => $achievement->reward_gold,
            'new_balance' => $user->fresh()->forum_gold_balance,
        ];
    }

    public function getUserAchievements(User $user)
    {
        $allAchievements = Achievement::where('is_active', true)->get();
        $userAchievements = $user->achievements;

        return [
            'unlocked' => $userAchievements,
            'locked' => $allAchievements->diff($userAchievements),
            'total_unlocked' => $userAchievements->count(),
            'total_available' => $allAchievements->count(),
            'completion_percentage' => $allAchievements->count() > 0
                ? ($userAchievements->count() / $allAchievements->count()) * 100
                : 0,
        ];
    }

    public function createAchievement($data)
    {
        return Achievement::create([
            'name' => $data['name'],
            'slug' => \Str::slug($data['name']),
            'description' => $data['description'],
            'reward_gold' => $data['reward_gold'],
            'icon' => $data['icon'] ?? '🏆',
            'category' => $data['category'],
            'unlock_criteria' => $data['unlock_criteria'],
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
