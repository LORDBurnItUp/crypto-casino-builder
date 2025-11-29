<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ForumGold\ForumGoldService;
use App\Services\Achievement\AchievementService;
use App\Models\Leaderboard;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $forumGoldService;
    protected $achievementService;

    public function __construct(
        ForumGoldService $forumGoldService,
        AchievementService $achievementService
    ) {
        $this->forumGoldService = $forumGoldService;
        $this->achievementService = $achievementService;
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();

        $stats = $this->forumGoldService->getUserStats($user);
        $achievements = $this->achievementService->getUserAchievements($user);

        return response()->json([
            'user' => $user,
            'stats' => $stats,
            'achievements' => $achievements,
        ]);
    }

    public function claimDailyBonus(Request $request)
    {
        $user = $request->user();

        try {
            $result = $this->forumGoldService->processDailyBonus($user);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function transactions(Request $request)
    {
        $user = $request->user();
        $limit = $request->query('limit', 50);
        $offset = $request->query('offset', 0);

        $transactions = $this->forumGoldService->getTransactionHistory($user, $limit, $offset);

        return response()->json($transactions);
    }

    public function achievements(Request $request)
    {
        $user = $request->user();
        $achievements = $this->achievementService->getUserAchievements($user);

        return response()->json($achievements);
    }

    public function claimAchievement(Request $request, $achievementId)
    {
        $user = $request->user();
        $achievement = \App\Models\Achievement::findOrFail($achievementId);

        try {
            $result = $this->achievementService->claimAchievementReward($user, $achievement);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function leaderboard(Request $request)
    {
        $category = $request->query('category', 'gold_earned');
        $period = $request->query('period', 'all_time');

        $periodDate = Leaderboard::getPeriodDate($period);

        $leaderboard = Leaderboard::where('category', $category)
            ->where('period', $period)
            ->where('period_date', $periodDate)
            ->with('user:id,username,avatar,level')
            ->orderBy('rank')
            ->limit(100)
            ->get();

        return response()->json($leaderboard);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'avatar' => 'nullable|url',
            'country' => 'nullable|string|max:2',
        ]);

        $user->update($request->only(['avatar', 'country']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }
}
