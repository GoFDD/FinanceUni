<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use App\Services\GamificationService;

class GamificationController extends Controller
{
    protected $gamification;

    public function __construct(GamificationService $gamification)
    {
        $this->gamification = $gamification;
    }

    /**
     * Listar conquistas do usuário (retorna progresso + unlocked_at)
     */
    public function listAchievements()
    {
        $user = Auth::user();

        $achievements = Achievement::all()->map(function ($achievement) use ($user) {

            $pivot = $user->achievements()
                ->where('achievement_id', $achievement->id)
                ->first();

            $hasAchievement = $pivot !== null;

            $progress = $hasAchievement ? ($pivot->pivot->progress ?? 0) : 0;
            $unlockedAt = $hasAchievement ? $pivot->pivot->unlocked_at : null;

            // status: se já coletou -> completo; se progress >= 100 e não coletou -> progresso; se progress > 0 -> progresso; else bloqueado
            if ($unlockedAt !== null) {
                $status = 'completo';
                $statusLabel = 'Completo';
            } elseif ($progress >= 100) {
                $status = 'progresso';
                $statusLabel = 'Em progresso';
            } elseif ($progress > 0) {
                $status = 'progresso';
                $statusLabel = 'Em progresso';
            } else {
                $status = 'bloqueado';
                $statusLabel = 'Bloqueado';
            }

            return [
                'id' => $achievement->id,
                'nome' => $achievement->title,
                'descricao' => $achievement->description,
                'icone' => $achievement->icon ?? 'Trophy',
                'xp_reward' => $achievement->xp_reward,
                'rarity' => $achievement->rarity,
                'progress' => $progress,
                'status' => $status,
                'statusLabel' => $statusLabel,
                'unlocked_at' => $unlockedAt,
            ];
        });

        return response()->json($achievements);
    }

    /**
     * Coletar / desbloquear conquista (manual)
     */
    public function unlock(Request $request, int $achievementId)
    {
        $user = Auth::user();

        // Retorna array com informação de xp dado
        $result = $this->gamification->unlockAchievement($user->id, $achievementId);

        return response()->json($result);
    }

    /**
     * Listar metas do usuário (system + user)
     */
    public function listGoals()
    {
        $user = Auth::user();

        // Metas do sistema
        $systemGoals = Goal::systemGoals()
            ->where('status', '!=', 'completed')
            ->get();

        // Metas do usuário
        $userGoals = Goal::userGoals($user->id)
            ->where('status', '!=', 'completed')
            ->get();

        $goals = $systemGoals->merge($userGoals)->map(function ($goal) use ($user) {

            $progressPercent = min($goal->progress, 100);
            $restante = max($goal->target_value - $goal->current_value, 0);

            return [
                'id'          => $goal->id,
                'nome'        => $goal->title,
                'categoria'   => $goal->category,
                'percentual'  => round($progressPercent, 2),
                'atual'       => number_format($goal->current_value, 2, ',', '.'),
                'meta'        => number_format($goal->target_value, 2, ',', '.'),
                'restante'    => number_format($restante, 2, ',', '.'),
                'recompensaXP'=> $goal->xp_reward,
                'tipo'        => $goal->is_system_goal ? 'sistema' : 'pessoal',
            ];
        });

        return response()->json($goals);
    }

    /**
     * Completar meta (system goal)
     */
    public function completeGoal(int $goalId)
    {
        $user = Auth::user();

        $goal = $this->gamification->completeGoal($user->id, $goalId);

        // Opcional: desbloquear conquista "Meta Concluída" (ID fixo conforme seed)
        // $this->gamification->markAchievementProgress($user->id, X);

        return response()->json([
            'message' => 'Meta completada com sucesso!',
            'goal'    => $goal
        ]);
    }

    /**
     * Criar meta pessoal (user goal) — AO CRIAR, marcamos progresso na conquista "Primeira Meta Criada"
     */
    public function createUserGoal(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'target_value' => 'required|numeric|min:1',
            'category' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $goal = Goal::create([
            'user_id' => $user->id,
            'is_system_goal' => false,
            'title' => $validated['title'],
            'category' => $validated['category'] ?? null,
            'target_value' => $validated['target_value'],
            'current_value' => 0,
            'xp_reward' => 0,
            'grants_achievement' => false,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => 'active',
        ]);

        // SÓ marca o progresso (100%) — unlocked_at permanece null (usuário deve clicar para coletar)
        $FIRST_GOAL_ACHIEVEMENT_ID = 2;
        $this->gamification->markAchievementProgress($user->id, $FIRST_GOAL_ACHIEVEMENT_ID, 100);

        return response()->json([
            'message' => 'Meta criada com sucesso',
            'goal' => $goal
        ]);
    }
}
