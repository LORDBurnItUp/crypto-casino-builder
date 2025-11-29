<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\Game\SlotGameService;
use App\Services\Game\RouletteGameService;
use App\Services\Game\DiceGameService;
use App\Services\Achievement\AchievementService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    protected $achievementService;

    public function __construct(AchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    public function index()
    {
        $games = Game::where('is_active', true)->get();
        return response()->json($games);
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return response()->json($game);
    }

    public function playSlots(Request $request, SlotGameService $slotService)
    {
        $request->validate([
            'bet_amount' => 'required|numeric|min:1',
            'client_seed' => 'nullable|string',
        ]);

        $game = Game::where('slug', 'slots')->firstOrFail();
        $user = $request->user();

        try {
            $result = $slotService->play(
                $user,
                $game,
                $request->bet_amount,
                $request->client_seed
            );

            // Check for achievements
            $newAchievements = $this->achievementService->checkAndUnlockAchievements($user);

            $result['achievements'] = $newAchievements;

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function playRoulette(Request $request, RouletteGameService $rouletteService)
    {
        $request->validate([
            'bet_amount' => 'required|numeric|min:1',
            'bet_type' => 'required|in:number,color,even_odd,high_low,dozen',
            'bet_value' => 'required',
            'client_seed' => 'nullable|string',
        ]);

        $game = Game::where('slug', 'roulette')->firstOrFail();
        $user = $request->user();

        try {
            $result = $rouletteService->play(
                $user,
                $game,
                $request->bet_amount,
                $request->bet_type,
                $request->bet_value,
                $request->client_seed
            );

            $newAchievements = $this->achievementService->checkAndUnlockAchievements($user);
            $result['achievements'] = $newAchievements;

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function playDice(Request $request, DiceGameService $diceService)
    {
        $request->validate([
            'bet_amount' => 'required|numeric|min:1',
            'prediction' => 'required|in:over,under',
            'target_number' => 'required|integer|min:2|max:98',
            'client_seed' => 'nullable|string',
        ]);

        $game = Game::where('slug', 'dice')->firstOrFail();
        $user = $request->user();

        try {
            $result = $diceService->play(
                $user,
                $game,
                $request->bet_amount,
                $request->prediction,
                $request->target_number,
                $request->client_seed
            );

            $newAchievements = $this->achievementService->checkAndUnlockAchievements($user);
            $result['achievements'] = $newAchievements;

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $history = $user->gameSessions()
            ->with('game')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($history);
    }
}
