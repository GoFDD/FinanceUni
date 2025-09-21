<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

// Registro e confirmação
Route::post('/register', [AuthController::class, 'register']);
Route::get('/confirm-email/{token}', [AuthController::class, 'confirmEmail']);

// Login
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Exemplo de rota protegida (dados do usuário logado)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
