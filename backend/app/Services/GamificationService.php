<?php

namespace App\Services;

use App\Models\XpHistory;
use App\Models\Achievement;
use App\Models\Goal;
use App\Models\User;
use Carbon\Carbon;

class GamificationService
{
    /**
     * 🔹 Adiciona XP genérico (usado por metas, conquistas, login, etc.)
     */
    public function addXp($userId, $amount, $reason, $source = 'sistema')
    {
        // Cria registro no histórico
        XpHistory::create([
            'user_id' => $userId,
            'source' => $source,
            'amount' => $amount,
            'description' => $reason,
        ]);

        // Soma XP total do usuário
        $user = User::find($userId);
        if ($user) {
            $user->xp += $amount;
            $user->save();
        }
    }

    public function completeGoal($userId, $goalId)
    {
        $goal = Goal::where('id', $goalId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if (!$goal->is_completed) {
            $goal->update(['is_completed' => true]);
            $this->addXp($userId, $goal->xp_reward, "Meta concluída: {$goal->title}", 'meta');
        }

        return $goal;
    }

    public function unlockAchievement($userId, $achievementId)
    {
        $achievement = Achievement::where('id', $achievementId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if (is_null($achievement->unlocked_at)) {
            $achievement->update(['unlocked_at' => now()]);
            $this->addXp($userId, $achievement->xp_reward, "Conquista desbloqueada: {$achievement->title}", 'conquista');
        }

        return $achievement;
    }

    /**
     * 🎯 Login diário — controla streak e XP diário com bônus progressivo
     */
    public function handleDailyLogin($user)
    {
        $today = Carbon::now();
        $lastLogin = $user->last_login ? Carbon::parse($user->last_login) : null;

        // XP base por login
        $baseXp = 10;
        $bonusXp = 0;

        // Se nunca logou antes
        if (!$lastLogin) {
            $user->streak = 1;
            $user->best_streak = 1;
            $user->last_login = $today;
            $user->save();

            // Primeiro login diário
            $this->addXp($user->id, $baseXp, 'Primeiro login diário', 'login_diario');
            return;
        }

        $hoursSinceLastLogin = $today->diffInHours($lastLogin);

        // Já logou hoje → sem XP
        if ($hoursSinceLastLogin < 24 && $lastLogin->isSameDay($today)) {
            return;
        }

        // Se foi ontem, continua o streak
        if ($lastLogin->isYesterday()) {
            $user->streak += 1;

            // bônus de consistência = 10xp extras por manter streak
            $bonusXp = 10;
        } else {
            // streak quebrada
            $user->streak = 1;
        }

        // Atualiza recorde
        if ($user->streak > $user->best_streak) {
            $user->best_streak = $user->streak;
        }

        $user->last_login = $today;
        $user->save();

        // Total de XP (base + bônus se streak continuar)
        $totalXp = $baseXp + $bonusXp;
        $this->addXp($user->id, $totalXp, 'Login diário', 'login_diario');
    }
}
