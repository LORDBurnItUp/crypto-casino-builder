<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'score',
        'period',
        'period_date',
        'rank',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'period_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function updateUserScore($userId, $category, $score, $period = 'all_time')
    {
        $periodDate = self::getPeriodDate($period);

        $leaderboard = self::updateOrCreate(
            [
                'user_id' => $userId,
                'category' => $category,
                'period' => $period,
                'period_date' => $periodDate,
            ],
            ['score' => $score]
        );

        return $leaderboard;
    }

    public static function getPeriodDate($period)
    {
        switch ($period) {
            case 'daily':
                return today();
            case 'weekly':
                return now()->startOfWeek()->toDateString();
            case 'monthly':
                return now()->startOfMonth()->toDateString();
            case 'all_time':
            default:
                return '2025-01-01'; // Fixed date for all-time
        }
    }

    public static function calculateRanks($category, $period)
    {
        $periodDate = self::getPeriodDate($period);

        $leaderboards = self::where('category', $category)
            ->where('period', $period)
            ->where('period_date', $periodDate)
            ->orderBy('score', 'desc')
            ->get();

        $rank = 1;
        foreach ($leaderboards as $entry) {
            $entry->rank = $rank++;
            $entry->save();
        }
    }
}
