<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\PluggyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
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

    // Pluggy
    Route::prefix('pluggy')->group(function () {
        Route::get('/connect-token', [PluggyController::class, 'generateConnectToken']);
        Route::post('/save-item', [PluggyController::class, 'saveItem']);
        Route::get('/accounts', [PluggyController::class, 'listUserAccounts']);
        Route::delete('/accounts/{accountId}', [PluggyController::class, 'deleteAccount']);
    });

    // TRANSACTIONS
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy']);

    // SUMMARY
    Route::get('/transactions/expense-summary', [TransactionController::class, 'expenseSummary']);

    // CATEGORIES
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

});