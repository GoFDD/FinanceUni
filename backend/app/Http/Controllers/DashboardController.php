<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achievement;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ==========================
        //   1. MIS INFORMAÇÕES DO USUÁRIO
        // ==========================
        $userData = [
            'name'  => $user->name,
            'title' => $user->title,
            'xp'    => $user->xp,
            'level' => $user->level,
        ];

        // ==========================
        //   2. CALCULAR OU PEGAR O STREAK
        // ==========================
        $streakData = [
            'streak'       => $user->streak ?? 0,
            'best_streak'  => $user->best_streak ?? 0,
            'last_login'   => $user->last_login ?? null,
        ];

        // ==========================
        //   3. LISTA DE CONQUISTAS
        // ==========================
        $conquistas = Achievement::all()->map(function ($achievement) use ($user) {

            // Verifica se o usuário já desbloqueou
            $hasAchievement = $user->achievements()
                ->where('achievement_id', $achievement->id)
                ->exists();

            return [
                'id'          => $achievement->id,
                'nome'        => $achievement->title,
                'descricao'   => $achievement->description,
                'icone'       => $achievement->icon, // texto, svg name etc
                'status'      => $hasAchievement ? 'completo' : 'bloqueado',
                'statusLabel' => $hasAchievement ? 'Completo' : 'Bloqueado',
            ];
        });

        // ==========================
        //   4. (OPCIONAL) METAS FUTURAS
        // ==========================

        // Por enquanto, retornamos vazio — depois integramos
        $metas = [];

        // ==========================
        //   5. RETORNAR TUDO EM UMA ÚNICA RESPOSTA
        // ==========================
        return response()->json([
            'user'        => $userData,
            'streak'      => $streakData,
            'conquistas'  => $conquistas,
            'metas'       => $metas,
        ]);
    }
}
