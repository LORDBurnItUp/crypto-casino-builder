<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'login_date',
        'bonus_amount',
        'streak_days',
    ];

    protected $casts = [
        'login_date' => 'date',
        'bonus_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function calculateBonusAmount($streakDays)
    {
        // Progressive bonus based on streak
        $baseBonus = 1000;

        if ($streakDays >= 30) {
            return 20000;
        } elseif ($streakDays >= 14) {
            return 10000;
        } elseif ($streakDays >= 7) {
            return 5000;
        } elseif ($streakDays >= 3) {
            return 2000;
        }

        return $baseBonus;
    }
}
