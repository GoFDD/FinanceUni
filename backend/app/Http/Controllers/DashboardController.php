<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\Goal;
use App\Models\XpHistory;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Retorna todos os dados do dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Processa login diário (atualiza streak e dá XP)
        $this->gamificationService->handleDailyLogin($user);
        
        // Recarrega dados do usuário
        $user->refresh();

        // Calcula XP total
        $totalXp = XpHistory::where('user_id', $user->id)->sum('amount');
        $level = $this->calculateLevel($totalXp);

        // ==========================
        //   1. INFORMAÇÕES DO USUÁRIO
        // ==========================
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'title' => $this->getLevelTitle($level),
            'xp' => $totalXp,
            'level' => $level,
        ];

        // ==========================
        //   2. STREAK
        // ==========================
        $streakData = [
            'streak' => $user->streak ?? 0,
            'best_streak' => $user->best_streak ?? 0,
            'last_login' => $user->last_login,
        ];

        // ==========================
        //   3. CONQUISTAS
        // ==========================
        $conquistas = $this->getAchievements($user);

        // ==========================
        //   4. METAS (Sistema + Pessoais)
        // ==========================
        $metas = $this->getGoals($user);

        // ==========================
        //   5. CARDS DE RESUMO
        // ==========================
        $resumo = $this->getSummaryCards($user, $totalXp);

        // ==========================
        //   6. RETORNAR TUDO
        // ==========================
        return response()->json([
            'user' => $userData,
            'streak' => $streakData,
            'conquistas' => $conquistas,
            'metas' => $metas,
            'resumo' => $resumo,
        ]);
    }

    /**
     * Busca conquistas com status do usuário
     */
    private function getAchievements($user)
    {
        // Busca todas as conquistas do sistema
        $allAchievements = Achievement::all();

        return $allAchievements->map(function ($achievement) use ($user) {
            // Verifica se o usuário tem essa conquista
            $userAchievement = $user->achievements()
                ->where('achievement_id', $achievement->id)
                ->first();

            $hasAchievement = $userAchievement !== null;
            $progress = $hasAchievement ? ($userAchievement->pivot->progress ?? 0) : 0;

            // Define o status
            if ($hasAchievement && $progress >= 100) {
                $status = 'completo';
                $statusLabel = 'Completo';
            } elseif ($hasAchievement && $progress > 0) {
                $status = 'progresso';
                $statusLabel = 'Em Progresso';
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
                'status' => $status,
                'statusLabel' => $statusLabel,
                'progress' => $progress,
                'unlocked_at' => $hasAchievement ? $userAchievement->pivot->unlocked_at : null,
            ];
        });
    }

    /**
     * Busca metas do sistema e pessoais
     */
    private function getGoals($user)
    {
        // Metas do sistema (gamificação)
        $systemGoals = Goal::systemGoals()
            ->where('status', '!=', 'completed')
            ->get();

        // Metas pessoais do usuário
        $userGoals = Goal::userGoals($user->id)
            ->where('status', '!=', 'completed')
            ->get();

        // Combina as duas listas
        $allGoals = $systemGoals->merge($userGoals);

        return $allGoals->map(function ($goal) {
            $percentual = min($goal->progress, 100);
            $restante = max($goal->target_value - $goal->current_value, 0);

            return [
                'id' => $goal->id,
                'nome' => $goal->title,
                'icone' => $this->getCategoryIcon($goal->category),
                'tipo' => $goal->is_system_goal ? 'sistema' : 'pessoal',
                'atual' => number_format($goal->current_value, 2, ',', '.'),
                'meta' => number_format($goal->target_value, 2, ',', '.'),
                'restante' => number_format($restante, 2, ',', '.'),
                'percentual' => round($percentual, 2),
                'recompensaXP' => $goal->xp_reward ?? 0,
                'recompensaNome' => $goal->grants_achievement 
                    ? 'Conquista Desbloqueada' 
                    : 'Meta Concluída',
                'categoria' => $goal->category,
            ];
        });
    }

    /**
     * Calcula dados dos cards de resumo
     */
    private function getSummaryCards($user, $totalXp)
    {
        // Total de metas ativas
        $metasAtivas = Goal::where('user_id', $user->id)
            ->where('status', '!=', 'completed')
            ->count();

        // Total de metas concluídas
        $metasConcluidas = Goal::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Total de conquistas desbloqueadas
        $conquistasDesbloqueadas = $user->achievements()
            ->wherePivot('progress', '>=', 100)
            ->count();

        // Total de conquistas disponíveis
        $conquistasTotais = Achievement::count();

        // TODO: Implementar quando tiver tabela de contas
        $saldoTotal = 0;

        return [
            'saldo_total' => $saldoTotal,
            'saldo_formatado' => 'R$ ' . number_format($saldoTotal, 2, ',', '.'),
            
            'metas_ativas' => $metasAtivas,
            'metas_concluidas' => $metasConcluidas,
            
            'conquistas_desbloqueadas' => $conquistasDesbloqueadas,
            'conquistas_totais' => $conquistasTotais,
            
            'streak' => $user->streak ?? 0,
            'best_streak' => $user->best_streak ?? 0,

            'total_xp' => $totalXp,
        ];
    }

    /**
     * Calcula nível baseado no XP total
     */
    private function calculateLevel($xp)
    {
        return intdiv($xp, 1000) + 1; // 1000 XP por nível
    }

    /**
     * Retorna título baseado no nível
     */
    private function getLevelTitle($level)
    {
        $titles = [
            1 => 'Iniciante',
            2 => 'Aprendiz',
            3 => 'Poupador',
            4 => 'Investidor',
            5 => 'Mestre das Finanças',
            6 => 'Expert Financeiro',
            7 => 'Guru do Dinheiro',
            8 => 'Magnata',
            9 => 'Lenda das Finanças',
            10 => 'Titã Financeiro',
        ];

        return $titles[$level] ?? 'Titã Nível ' . $level;
    }

    /**
     * Retorna ícone baseado na categoria da meta
     */
    private function getCategoryIcon($category)
    {
        $icons = [
            'economia' => 'PiggyBank',
            'investimento' => 'TrendingUp',
            'divida' => 'CreditCard',
            'educacao' => 'BookOpen',
            'viagem' => 'Plane',
            'emergencia' => 'Shield',
            'casa' => 'Home',
            'carro' => 'Car',
        ];

        return $icons[$category] ?? 'Target';
    }
}