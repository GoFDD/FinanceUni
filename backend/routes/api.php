<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GamificationController;
use Illuminate\Http\Request;

// ==========================================
// ROTAS PÚBLICAS (sem autenticação)
// ==========================================

// Autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Verificação de e-mail
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail']);
Route::post('/resend-verification', [AuthController::class, 'resendVerification']);

// ==========================================
// ROTAS PROTEGIDAS (requer autenticação)
// ==========================================

Route::middleware('auth:sanctum')->group(function () {
    
    // ========== AUTENTICAÇÃO ==========
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // ========== USUÁRIO ==========
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/user/profile', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'name' => $user->name,
            'level' => $user->level,
            'xp' => $user->xp,
            'title' => $user->title,
            'streak' => $user->streak,
            'best_streak' => $user->best_streak,
        ]);
    });
    
    Route::get('/user/streak', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'streak' => $user->streak,
            'best_streak' => $user->best_streak,
            'last_login' => $user->last_login,
        ]);
    });
    
    // ========== DASHBOARD ==========
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // ========== GAMIFICAÇÃO ==========
    Route::prefix('gamification')->group(function () {
        // Dashboard de gamificação (detalhado)
        Route::get('/dashboard', [GamificationController::class, 'getDashboard']);
        
        // Metas
        Route::get('/system-goals', [GamificationController::class, 'listSystemGoals']);
        Route::get('/user-goals', [GamificationController::class, 'listUserGoals']);
        Route::post('/goals/{goal}/complete', [GamificationController::class, 'completeGoal']);
        
        // Conquistas
        Route::get('/achievements', [GamificationController::class, 'listAchievements']);
    });
});