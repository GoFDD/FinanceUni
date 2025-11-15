<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\GamificationController;

// Registro e confirmação
Route::post('/register', [AuthController::class, 'register']);
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);
Route::post('/resend-verification', [AuthController::class, 'resendVerification']);

// Login
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); 
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Gamificação
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/gamification/dashboard', [GamificationController::class, 'getDashboard']);
    Route::post('/gamification/complete-goal/{goal}', [GamificationController::class, 'completeGoal']);
});

// Rota para obter streak do usuário
Route::middleware('auth:sanctum')->get('/user/streak', function (Request $request) {
    $user = $request->user();

    return response()->json([
        'streak' => $user->streak,
        'best_streak' => $user->best_streak,
        'last_login' => $user->last_login,
    ]);
});

Route::middleware('auth:sanctum')->get('/user/profile', function (Request $request) {
    $user = $request->user();

    return response()->json([
        'name' => $user->name,
        'level' => $user->level,
        'xp' => $user->xp,
        'progress' => $user->xp_progress,
        'title' => $user->title,
        'streak' => $user->streak,
        'best_streak' => $user->best_streak,
    ]);
});

