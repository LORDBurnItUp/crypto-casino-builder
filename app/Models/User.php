<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'forum_gold_balance',
        'level',
        'xp',
        'current_streak',
        'last_login_date',
        'avatar',
        'country',
        'is_active',
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_date' => 'date',
        'forum_gold_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_banned' => 'boolean',
    ];

    // Relationships
    public function forumGoldTransactions()
    {
        return $this->hasMany(ForumGoldTransaction::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function gameSessions()
    {
        return $this->hasMany(GameSession::class);
    }

    public function dailyBonuses()
    {
        return $this->hasMany(DailyBonus::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('unlocked_at', 'reward_claimed')
            ->withTimestamps();
    }

    public function purchases()
    {
        return $this->hasMany(UserPurchase::class);
    }

    public function leaderboards()
    {
        return $this->hasMany(Leaderboard::class);
    }

    // Helper Methods
    public function addForumGold($amount, $type, $description, $referenceId = null, $referenceType = null)
    {
        $this->forum_gold_balance += $amount;
        $this->save();

        return $this->forumGoldTransactions()->create([
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
            'balance_after' => $this->forum_gold_balance,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }

    public function subtractForumGold($amount, $type, $description, $referenceId = null, $referenceType = null)
    {
        if ($this->forum_gold_balance < $amount) {
            throw new \Exception('Insufficient forum gold balance');
        }

        $this->forum_gold_balance -= $amount;
        $this->save();

        return $this->forumGoldTransactions()->create([
            'amount' => -$amount,
            'type' => $type,
            'description' => $description,
            'balance_after' => $this->forum_gold_balance,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }

    public function addXp($amount)
    {
        $this->xp += $amount;
        $oldLevel = $this->level;

        // Level up formula: Level = floor(sqrt(XP / 100))
        $newLevel = floor(sqrt($this->xp / 100));

        if ($newLevel > $oldLevel) {
            $this->level = $newLevel;
            // Award level up bonus
            $bonus = 1000 * $newLevel;
            $this->addForumGold($bonus, 'bonus', "Level {$newLevel} reached! Bonus reward.");
        }

        $this->save();
        return $newLevel > $oldLevel;
    }

    public function getTotalDonations()
    {
        return $this->donations()->where('status', 'completed')->sum('real_money_amount');
    }

    public function getTotalGamesPlayed()
    {
        return $this->gameSessions()->count();
    }

    public function getWinRate()
    {
        $totalGames = $this->gameSessions()->count();
        if ($totalGames === 0) return 0;

        $wins = $this->gameSessions()->where('is_win', true)->count();
        return ($wins / $totalGames) * 100;
    }
}
