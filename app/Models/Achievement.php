<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'reward_gold',
        'icon',
        'category',
        'unlock_criteria',
        'is_active',
        'unlock_count',
    ];

    protected $casts = [
        'reward_gold' => 'decimal:2',
        'unlock_criteria' => 'array',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withPivot('unlocked_at', 'reward_claimed')
            ->withTimestamps();
    }

    public function checkUnlock($user)
    {
        // Check if user already has this achievement
        if ($user->achievements()->where('achievement_id', $this->id)->exists()) {
            return false;
        }

        $criteria = $this->unlock_criteria;
        $type = $criteria['type'] ?? null;
        $count = $criteria['count'] ?? 0;

        switch ($type) {
            case 'games_played':
                return $user->getTotalGamesPlayed() >= $count;

            case 'total_donations':
                return $user->getTotalDonations() >= $count;

            case 'gold_earned':
                $totalEarned = $user->forumGoldTransactions()
                    ->where('amount', '>', 0)
                    ->sum('amount');
                return $totalEarned >= $count;

            case 'causes_supported':
                $causeCount = $user->donations()
                    ->where('status', 'completed')
                    ->distinct('cause_id')
                    ->count('cause_id');
                return $causeCount >= $count;

            default:
                return false;
        }
    }

    public function unlockForUser($user)
    {
        $user->achievements()->attach($this->id, [
            'unlocked_at' => now(),
            'reward_claimed' => false,
        ]);

        $this->unlock_count++;
        $this->save();

        return true;
    }
}
