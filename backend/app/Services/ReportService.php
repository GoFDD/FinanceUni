<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function generateReport($user, $from, $to, $type, $accountId, $preset)
    {
        $query = Transaction::where('user_id', $user->id);

        if ($from) $query->where('date', '>=', $from);
        if ($to)   $query->where('date', '<=', $to);

        if ($type === 'despesas') $query->where('type', 'expense');
        if ($type === 'receitas') $query->where('type', 'income');

        if ($accountId) $query->where('account_id', $accountId);

        $transactions = $query->get();

        // ======= SUMMARY =======
        $summary = [
            'total_income'  => (float) $transactions->where('type', 'income')->sum('amount'),
            'total_expense' => (float) $transactions->where('type', 'expense')->sum('amount'),
            'balance'       => 0,
            'count'         => $transactions->count()
        ];
        $summary['balance'] = $summary['total_income'] - $summary['total_expense'];

        // ======= DATA =======
        if ($preset === 'categorias') {
            $data = $transactions
                ->groupBy('category_name')
                ->map(fn($t, $cat) => [
                    'key' => $cat,
                    'label' => $cat,
                    'value' => (float) $t->sum('amount'),
                    'subtitle' => $t->count() . ' transações',
                ])
                ->values();
        }

        if ($preset === 'por-conta') {
            $data = $transactions
                ->groupBy('account_name')
                ->map(fn($t, $acc) => [
                    'key' => $acc,
                    'label' => $acc,
                    'value' => (float) $t->sum('amount'),
                    'subtitle' => $t->count() . ' transações',
                ])
                ->values();
        }

        if ($preset === 'tempo') {
            $data = $transactions
                ->groupBy(fn($t) => date('Y-m', strtotime($t->date)))
                ->map(fn($t, $month) => [
                    'key' => $month,
                    'label' => $month,
                    'value' => (float) $t->sum('amount'),
                    'subtitle' => $t->count() . ' transações',
                ])
                ->values();
        }

        // ===== MINI CHARTS =====
        $mini = [
            'months' => [],
            'incomeExpense' => [],
            'top' => [],
            'accounts' => [],
        ];

        // últimas 6
        $lastMonths = $transactions
            ->groupBy(fn($t) => date('Y-m', strtotime($t->date)))
            ->sortKeys()
            ->take(6);

        $mini['months'] = $lastMonths->keys()->values();

        $mini['incomeExpense'] = [
            [
                'label' => 'Receitas',
                'data' => $lastMonths->map(fn($t) => (float) $t->where('type', 'income')->sum('amount'))->values(),
            ],
            [
                'label' => 'Despesas',
                'data' => $lastMonths->map(fn($t) => (float) $t->where('type', 'expense')->sum('amount'))->values(),
            ],
        ];

        // top categorias
        $mini['top'] = $transactions
            ->groupBy('category_name')
            ->map(fn($t, $cat) => ['label' => $cat, 'value' => (float) $t->sum('amount')])
            ->sortByDesc('value')
            ->take(5)
            ->values();

        // por conta
        $mini['accounts'] = $transactions
            ->groupBy('account_name')
            ->map(fn($t, $acc) => ['label' => $acc, 'value' => (float) $t->sum('amount')])
            ->values();

        return [
            'summary' => $summary,
            'data' => $data,
            'mini' => $mini,
        ];
    }
}
