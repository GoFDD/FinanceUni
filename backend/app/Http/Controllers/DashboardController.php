<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\Goal;
use App\Models\XpHistory;
use App\Models\Transaction; 
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
        //   INFORMAÇÕES DO USUÁRIO
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
        //    STREAK
        // ==========================
        $streakData = [
            'streak' => $user->streak ?? 0,
            'best_streak' => $user->best_streak ?? 0,
            'last_login' => $user->last_login,
        ];

        // ==========================
        //    CONQUISTAS
        // ==========================
        $conquistas = $this->getAchievements($user);

        // ==========================
        //    METAS (Sistema + Pessoais)
        // ==========================
        $metas = $this->getGoals($user);

        // ==========================
        //    CARDS DE RESUMO
        // ==========================
        $resumo = $this->getSummaryCards($user, $totalXp);

        // ==========================
        //    RETORNAR TUDO
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
        $allAchievements = Achievement::all();

        return $allAchievements->map(function ($achievement) use ($user) {

            $pivot = $user->achievements()
                ->where('achievement_id', $achievement->id)
                ->first();

            $hasAchievement = $pivot !== null;

            // progresso salvo na tabela pivot (0..100)
            $progress = $hasAchievement ? ($pivot->pivot->progress ?? 0) : 0;
            $unlockedAt = $hasAchievement ? $pivot->pivot->unlocked_at : null;

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
                'icone' => $achievement->icon ?? 'trophy',

                'progress' => (int) $progress,     // 0..100
                'status' => $status,
                'statusLabel' => $statusLabel,

                'xp_reward' => $achievement->xp_reward,
                'rarity' => $achievement->rarity,

                // se null => front mostra 'Pegar recompensa'
                'unlocked_at' => $unlockedAt,
            ];
        });
    }



    /**
     * Busca metas do sistema e pessoais
     */
    private function getGoals($user)
    {
        // Metas do sistema
        $systemGoals = Goal::systemGoals()
            ->where('status', '!=', 'completed')
            ->get();

        // Metas pessoais
        $userGoals = Goal::userGoals($user->id)
            ->where('status', '!=', 'completed')
            ->get();

        $allGoals = $systemGoals->merge($userGoals);

        return $allGoals->map(function ($goal) use ($user) {

            // ===============================
            //  Se for meta pessoal → calcular progresso
            // ===============================
            if (!$goal->is_system_goal) {

                // Somar receitas dentro do período
                $income = Transaction::where('user_id', $user->id)
                    ->where('type', 'income')
                    ->whereBetween('date', [$goal->start_date, $goal->end_date])
                    ->sum('amount');

                // Somar despesas dentro do período
                $expense = Transaction::where('user_id', $user->id)
                    ->where('type', 'expense')
                    ->whereBetween('date', [$goal->start_date, $goal->end_date])
                    ->sum('amount');

                // Atual
                $current = floatval($income) - floatval($expense);

                // Atualiza o valor atual temporariamente (não salva no DB)
                $goal->current_value = $current;
            }

            // ===============================
            //  Calcular percentual e restante
            // ===============================
            $percentual = $goal->target_value > 0
                ? min(100, ($goal->current_value / $goal->target_value) * 100)
                : 0;

            $restante = max($goal->target_value - $goal->current_value, 0);

            // ===============================
            //  Retorno final formatado
            // ===============================
            return [
                'id' => $goal->id,
                'nome' => $goal->title,
                'icone' => $this->getCategoryIcon($goal->category),
                'tipo' => $goal->is_system_goal ? 'sistema' : 'pessoal',

                // números
                'atual' => number_format($goal->current_value, 2, ',', '.'),
                'meta' => number_format($goal->target_value, 2, ',', '.'),
                'restante' => number_format($restante, 2, ',', '.'),

                'percentual' => round($percentual, 2),

                // XP só para metas do sistema
                'recompensaXP' => $goal->is_system_goal ? ($goal->xp_reward ?? 0) : 0,
                'recompensaNome' => $goal->is_system_goal
                    ? ($goal->grants_achievement ? 'Conquista Desbloqueada' : 'Meta Concluída')
                    : 'Meta Pessoal',

                'categoria' => $goal->category,

                // datas
                'start_date' => $goal->start_date,
                'end_date' => $goal->end_date,
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
        // Cálculo do saldo total (receitas - despesas)
        try {
            $receitas = Transaction::fromUser($user->id)->income()->sum('amount');
            $despesas  = Transaction::fromUser($user->id)->expense()->sum('amount');
        } catch (\Throwable $e) {
            //  caso as scopes não existam — filtra por campos
            $receitas = Transaction::where('user_id', $user->id)->where('type', 'income')->sum('amount');
            $despesas  = Transaction::where('user_id', $user->id)->where('type', 'expense')->sum('amount');
        }

        $saldoTotal = floatval($receitas) - floatval($despesas);

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
