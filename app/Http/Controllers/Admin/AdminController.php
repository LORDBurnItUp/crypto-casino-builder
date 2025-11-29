<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Game, Donation, CharitableCause, Achievement};
use App\Services\Donation\DonationService;
use App\Services\Achievement\AchievementService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users_today' => User::whereDate('last_login_date', today())->count(),
            'total_games_played' => \App\Models\GameSession::count(),
            'total_donations' => Donation::where('status', 'completed')->sum('real_money_amount'),
            'pending_donations' => Donation::where('status', 'pending')->sum('real_money_amount'),
            'total_gold_in_circulation' => User::sum('forum_gold_balance'),
        ];

        return response()->json($stats);
    }

    // User Management
    public function users(Request $request)
    {
        $users = User::withCount(['gameSessions', 'donations'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($users);
    }

    public function banUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_banned = true;
        $user->save();

        return response()->json(['message' => 'User banned successfully']);
    }

    public function unbanUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_banned = false;
        $user->save();

        return response()->json(['message' => 'User unbanned successfully']);
    }

    public function adjustUserGold(Request $request, $userId)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $user = User::findOrFail($userId);
        $amount = $request->amount;

        if ($amount > 0) {
            $user->addForumGold($amount, 'admin_adjustment', $request->description);
        } else {
            $user->subtractForumGold(abs($amount), 'admin_adjustment', $request->description);
        }

        return response()->json([
            'message' => 'Gold adjusted successfully',
            'new_balance' => $user->fresh()->forum_gold_balance,
        ]);
    }

    // Game Management
    public function games()
    {
        $games = Game::withCount('sessions')
            ->with(['sessions' => function ($q) {
                $q->selectRaw('game_id, SUM(bet_amount) as total_bets, SUM(win_amount) as total_wins')
                    ->groupBy('game_id');
            }])
            ->get();

        return response()->json($games);
    }

    public function updateGame(Request $request, $gameId)
    {
        $game = Game::findOrFail($gameId);

        $request->validate([
            'min_bet' => 'nullable|numeric|min:1',
            'max_bet' => 'nullable|numeric',
            'house_edge' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $game->update($request->only(['min_bet', 'max_bet', 'house_edge', 'is_active']));

        return response()->json([
            'message' => 'Game updated successfully',
            'game' => $game,
        ]);
    }

    // Donation Management
    public function donations(DonationService $donationService)
    {
        $donations = Donation::with(['user', 'cause'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($donations);
    }

    public function processDonation(Request $request, $donationId, DonationService $donationService)
    {
        $request->validate([
            'transaction_reference' => 'nullable|string',
        ]);

        $donation = Donation::findOrFail($donationId);

        try {
            $result = $donationService->processDonation($donation, $request->transaction_reference);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Charitable Cause Management
    public function causes()
    {
        $causes = CharitableCause::withCount('donations')->get();
        return response()->json($causes);
    }

    public function createCause(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:environment,water,food,children,medical,education,housing,general',
            'icon' => 'nullable|string',
            'organization' => 'nullable|string',
            'website' => 'nullable|url',
        ]);

        $cause = CharitableCause::create($request->all());

        return response()->json([
            'message' => 'Cause created successfully',
            'cause' => $cause,
        ], 201);
    }

    public function updateCause(Request $request, $causeId)
    {
        $cause = CharitableCause::findOrFail($causeId);
        $cause->update($request->all());

        return response()->json([
            'message' => 'Cause updated successfully',
            'cause' => $cause,
        ]);
    }

    // Achievement Management
    public function achievements()
    {
        $achievements = Achievement::withCount('users')->get();
        return response()->json($achievements);
    }

    public function createAchievement(Request $request, AchievementService $achievementService)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'reward_gold' => 'required|numeric',
            'category' => 'required|string',
            'unlock_criteria' => 'required|array',
        ]);

        $achievement = $achievementService->createAchievement($request->all());

        return response()->json([
            'message' => 'Achievement created successfully',
            'achievement' => $achievement,
        ], 201);
    }
}
