<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Game\GameController;
use App\Http\Controllers\User\{UserController, DonationController};
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Games list (public)
Route::get('/games', [GameController::class, 'index']);
Route::get('/games/{slug}', [GameController::class, 'show']);

// Public donation stats
Route::get('/donations/global-impact', [DonationController::class, 'globalImpact']);
Route::get('/donations/conversion-rate', [DonationController::class, 'conversionRate']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // User Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard']);
    Route::get('/profile', [UserController::class, 'dashboard']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    // Forum Gold & Daily Bonus
    Route::post('/daily-bonus', [UserController::class, 'claimDailyBonus']);
    Route::get('/transactions', [UserController::class, 'transactions']);

    // Achievements
    Route::get('/achievements', [UserController::class, 'achievements']);
    Route::post('/achievements/{id}/claim', [UserController::class, 'claimAchievement']);

    // Leaderboard
    Route::get('/leaderboard', [UserController::class, 'leaderboard']);

    // Games - Playing
    Route::post('/games/slots/play', [GameController::class, 'playSlots']);
    Route::post('/games/roulette/play', [GameController::class, 'playRoulette']);
    Route::post('/games/dice/play', [GameController::class, 'playDice']);
    Route::get('/games/history', [GameController::class, 'history']);

    // Donations
    Route::get('/donations/causes', [DonationController::class, 'causes']);
    Route::post('/donations/donate', [DonationController::class, 'donate']);
    Route::get('/donations/my-donations', [DonationController::class, 'myDonations']);
});

// Admin routes (Note: Create admin middleware before using)
// Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
//     Route::get('/dashboard', [AdminController::class, 'dashboard']);
//     Route::get('/users', [AdminController::class, 'users']);
//     Route::post('/users/{id}/ban', [AdminController::class, 'banUser']);
//     Route::post('/users/{id}/unban', [AdminController::class, 'unbanUser']);
//     Route::post('/users/{id}/adjust-gold', [AdminController::class, 'adjustUserGold']);
//     Route::get('/games', [AdminController::class, 'games']);
//     Route::put('/games/{id}', [AdminController::class, 'updateGame']);
//     Route::get('/donations', [AdminController::class, 'donations']);
//     Route::post('/donations/{id}/process', [AdminController::class, 'processDonation']);
//     Route::get('/causes', [AdminController::class, 'causes']);
//     Route::post('/causes', [AdminController::class, 'createCause']);
//     Route::put('/causes/{id}', [AdminController::class, 'updateCause']);
//     Route::get('/achievements', [AdminController::class, 'achievements']);
//     Route::post('/achievements', [AdminController::class, 'createAchievement']);
// });