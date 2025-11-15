<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Achievement;
use App\Models\XpHistory;
use App\DTO\GoalDTO;
use App\DTO\AchievementDTO;
use App\DTO\XpHistoryDTO;
use Illuminate\Support\Facades\Auth;

class GamificationController extends Controller
{
    // Retorna dados do dashboard (usuário, metas e conquistas)
    public function getDashboard()
    {
        $user = Auth::user();

        // Metas do sistema (gamificação)
        $systemGoals = Goal::systemGoals()->get()
            ->map(fn($g) => new GoalDTO(array_merge(
                $g->toArray(),
                ['progress' => $g->progress]
            )));

        // Metas pessoais do usuário
        $userGoals = Goal::userGoals($user->id)->get()
            ->map(fn($g) => new GoalDTO(array_merge(
                $g->toArray(),
                ['progress' => $g->progress]
            )));

        // Conquistas disponíveis
        $achievements = Achievement::all()
            ->map(fn($a) => new AchievementDTO($a->toArray()));

        // XP total do usuário
        $xpHistory = XpHistory::where('user_id', $user->id)->get();
        $totalXp = $xpHistory->sum('amount');

        return response()->json([
            'user' => [
                'name' => $user->name,
                'level' => $this->calculateLevel($totalXp),
                'xp' => $totalXp,
                'streak' => $this->calculateStreak($user->id),
            ],
            'system_goals' => $systemGoals,
            'user_goals' => $userGoals,
            'achievements' => $achievements
        ]);
    }

    // Completa uma meta do sistema
    public function completeGoal(Request $request, int $goalId)
    {
        $user = Auth::user();

        $goal = Goal::where('id', $goalId)
            ->where('user_id', $user->id)
            ->where('is_system_goal', true)
            ->firstOrFail();

        if ($goal->status === 'completed') {
            return response()->json(['message' => 'Meta já concluída'], 400);
        }

        $goal->status = 'completed';
        $goal->current_value = $goal->target_value;
        $goal->save();

        // Adiciona XP
        if ($goal->xp_reward > 0) {
            XpHistory::create([
                'user_id' => $user->id,
                'goal_id' => $goal->id,
                'amount' => $goal->xp_reward,
                'description' => "XP ganho ao completar a meta: {$goal->title}"
            ]);
        }

        // Cria conquista associada
        if ($goal->grants_achievement) {
            Achievement::create([
                'user_id' => $user->id,
                'title' => "Conquista: {$goal->title}",
                'description' => "Você completou a meta '{$goal->title}'",
                'xp_reward' => $goal->xp_reward,
                'status' => 'unlocked'
            ]);
        }

        return response()->json([
            'message' => 'Meta concluída com sucesso',
            'goal' => new GoalDTO($goal->toArray())
        ]);
    }

    //  calcula nível do usuário
    private function calculateLevel(int $xp): int
    {
        return intdiv($xp, 1000) + 1; // 1000 XP por nível
    }

    //  calcula streak de login do usuário
    private function calculateStreak(int $userId): int
    {
        return 5; 
    }

    // Lista todas metas do sistema
    public function listSystemGoals()
    {
        return Goal::systemGoals()->get();
    }

    // Lista metas do usuário 
    public function listUserGoals()
    {
        $user = Auth::user();
        return Goal::userGoals($user->id)->get();
    }

    // Lista conquistas do usuário 
    public function listAchievements()
    {
        $user = Auth::user();
        return Achievement::where('user_id', $user->id)->get();
    }

    public function getStreak()
    {
        $user = Auth::user();

        return response()->json([
            'streak' => $user->streak,
            'best_streak' => $user->best_streak,
            'last_login' => $user->last_login,
            'message' => "Você está com uma sequência de {$user->streak} dia(s)! Seu recorde é {$user->best_streak}."
        ]);
    }

}
