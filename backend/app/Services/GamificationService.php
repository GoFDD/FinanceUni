<?php

namespace App\Services;

use App\Models\XpHistory;
use App\Models\Achievement;
use App\Models\Goal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GamificationService
{
    /**
     *  Adiciona XP ao usuário (centralizado)
     */
    public function addXp($userId, $amount, $reason, $source = 'sistema')
    {
        DB::transaction(function () use ($userId, $amount, $reason, $source) {

            // Cria registro no histórico
            XpHistory::create([
                'user_id' => $userId,
                'source' => $source,
                'amount' => $amount,
                'description' => $reason,
            ]);

            // Atualiza o XP total REAL do usuário
            $user = User::find($userId);
            if ($user) {
                $user->xp += $amount;
                $user->save();
            }
        });
    }

    /**
     *  Completar meta → XP + histórico
     *  (mantive a sua implementação, apenas garantindo retorno)
     */
    public function completeGoal($userId, $goalId)
    {
        $goal = Goal::where('id', $goalId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($goal->status !== 'completed') {
            $goal->update(['status' => 'completed']);

            if ($goal->xp_reward && $goal->xp_reward > 0) {
                $this->addXp(
                    $userId,
                    $goal->xp_reward,
                    "Meta concluída: {$goal->title}",
                    'meta'
                );
            }
        }

        return $goal;
    }

    /**
     * Marca progresso de uma conquista para um usuário (sem coletar XP).
     * progress: 0..100
     * Se já existir pivot, atualiza progress; se não existir, attach.
     * NOTA: unlocked_at NÃO é definido aqui (manual claim).
     */
    public function markAchievementProgress($userId, $achievementId, $progress = 0)
    {
        DB::transaction(function () use ($userId, $achievementId, $progress) {
            $achievement = Achievement::findOrFail($achievementId);

            // Se já existe, atualiza progress (mas não altera unlocked_at)
            $existing = $achievement->users()->where('user_id', $userId)->first();

            if (!$existing) {
                $achievement->users()->attach($userId, [
                    'progress' => min(100, (int)$progress),
                    'unlocked_at' => null,
                ]);
            } else {
                $achievement->users()->updateExistingPivot($userId, [
                    'progress' => min(100, (int)$progress),
                    // keep unlocked_at as is (do not set)
                ]);
            }
        });
    }

    /**
     * Desbloquear/Coletar conquista: atribui unlocked_at e entrega XP quando aplicável.
     * Retorna array com informações úteis (xp_earned, achievement)
     */
    public function unlockAchievement($userId, $achievementId)
    {
        return DB::transaction(function () use ($userId, $achievementId) {
            $achievement = Achievement::findOrFail($achievementId);

            // pega pivot (se existir)
            $userAchievement = $achievement->users()
                ->where('user_id', $userId)
                ->first();

            // Se já coletado (unlocked_at != null) => nada a fazer
            if ($userAchievement && $userAchievement->pivot->unlocked_at) {
                return [
                    'message' => 'Já coletado',
                    'xp_earned' => 0,
                    'achievement' => $achievement
                ];
            }

            // Caso a conquista seja auto_claim (ex.: Primeiro Login), então damos XP imediato
            if ($achievement->auto_claim) {
                // marcamos unlocked_at
                $achievement->users()->syncWithoutDetaching([
                    $userId => [
                        'progress' => 100,
                        'unlocked_at' => now(),
                    ]
                ]);

                // dá XP
                $this->addXp(
                    $userId,
                    $achievement->xp_reward,
                    "Conquista desbloqueada (auto): {$achievement->title}",
                    'conquista'
                );

                return [
                    'message' => 'Auto claim entregue',
                    'xp_earned' => $achievement->xp_reward,
                    'achievement' => $achievement
                ];
            }

            // Caso manual: apenas atribuimos unlocked_at agora (coleta), e damos XP
            // Se pivot não existir, criamos com progress 100 e unlocked_at (caso algo inconsistente)
            if (!$userAchievement) {
                $achievement->users()->attach($userId, [
                    'progress' => 100,
                    'unlocked_at' => now(),
                ]);
            } else {
                $achievement->users()->updateExistingPivot($userId, [
                    'progress' => 100,
                    'unlocked_at' => now(),
                ]);
            }

            // Dar XP ao usuário
            $this->addXp(
                $userId,
                $achievement->xp_reward,
                "Conquista coletada: {$achievement->title}",
                'conquista'
            );

            return [
                'message' => 'Recompensa coletada',
                'xp_earned' => $achievement->xp_reward,
                'achievement' => $achievement
            ];
        });
    }

    /**
     *  Login diário — streak + XP diário
     */
    public function handleDailyLogin($user)
    {
        $today = Carbon::now();
        $lastLogin = $user->last_login ? Carbon::parse($user->last_login) : null;

        $baseXp = 10;
        $bonusXp = 0;

        // PRIMEIRO LOGIN
        if (!$lastLogin) {
            $user->streak = 1;
            $user->best_streak = 1;
            $user->last_login = $today;
            $user->save();

            // Auto-claim primeiro login (exemplo: achievement id 1)
            // Se essa conquista for auto_claim no DB, a função unlockAchievement cuidará
            $this->unlockAchievement($user->id, 1);

            $this->addXp($user->id, $baseXp, 'Primeiro login diário', 'login_diario');
            return;
        }

        // Já logou hoje
        if ($lastLogin->isSameDay($today)) {
            return;
        }

        // Logou ontem -> streak++
        if ($lastLogin->isYesterday()) {
            $user->streak += 1;
            $bonusXp = 10;
        } else {
            // perdeu streak
            $user->streak = 1;
        }

        if ($user->streak > $user->best_streak) {
            $user->best_streak = $user->streak;
        }

        $user->last_login = $today;
        $user->save();

        // Conquistas de streak (ex.: IDs 3,5,7)
        if ($user->streak === 7) {
            $this->markAchievementProgress($user->id, 3, 100);
        }

        if ($user->streak === 30) {
            $this->markAchievementProgress($user->id, 5, 100);
        }

        if ($user->streak === 100) {
            $this->markAchievementProgress($user->id, 7, 100);
        }

        // XP final do login
        $totalXp = $baseXp + $bonusXp;
        $this->addXp($user->id, $totalXp, 'Login diário', 'login_diario');
    }
}
