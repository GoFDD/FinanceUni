<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function categories()
    {
        $user = Auth::user();

        return Category::where('user_id', $user->id)
            ->orWhereNull('user_id')
            ->orderBy('name')
            ->get(['id', 'name', 'type']);
    }

    public function report(Request $request)
    {
        $user = Auth::user();

        // filtros
        $from   = $request->get("from");
        $to     = $request->get("to");
        $type   = $request->get("type", "todas");
        $preset = $request->get("preset", "categorias");

        // ===========================
        // BASE QUERY (compatível com seu sistema)
        // ===========================
        $query = Transaction::where("user_id", $user->id);

        if ($from && $to) {
            $query->whereBetween("date", [$from, $to]);
        }

        if ($type === "receitas") {
            $query->where("type", "income");
        } elseif ($type === "despesas") {
            $query->where("type", "expense");
        }

        $transactions = $query->get();

        // ===========================
        // SUMMARY
        // ===========================
        $summary = [
            "total_income"  => $transactions->where("type", "income")->sum("amount"),
            "total_expense" => $transactions->where("type", "expense")->sum("amount"),
            "balance"       => $transactions->where("type", "income")->sum("amount")
                                - $transactions->where("type", "expense")->sum("amount"),
            "count"         => $transactions->count(),
        ];

        // ===========================
        // CHART PRINCIPAL
        // ===========================
        if ($preset === "categorias") {
            $group = $transactions
                ->groupBy("category_id")
                ->map(function ($items, $id) {
                    $cat = Category::find($id);
                    return [
                        "key"      => $id ?? 0,
                        "label"    => $cat->name ?? "Sem categoria",
                        "value"    => $items->sum("amount"),
                        "subtitle" => $items->count() . " transações"
                    ];
                })
                ->values();
        }

        if ($preset === "tempo") {
            $group = $transactions
                ->groupBy("date")
                ->map(fn ($items, $d) => [
                    "key"      => $d,
                    "label"    => $d,
                    "value"    => $items->sum("amount"),
                    "subtitle" => $items->count() . " transações"
                ])
                ->values();
        }

        // ===========================
        // MINI CHARTS
        // ===========================
        $months = [];
        $incomeSeries = [];
        $expenseSeries = [];

        $monthly = Transaction::where("user_id", $user->id)
            ->whereBetween("date", [$from, $to])
            ->get()
            ->groupBy(fn ($t) => substr($t->date, 0, 7));

        foreach ($monthly as $month => $items) {
            $months[] = $month;
            $incomeSeries[] = $items->where("type", "income")->sum("amount");
            $expenseSeries[] = $items->where("type", "expense")->sum("amount");
        }

        // top categorias
        $top = $transactions
            ->groupBy("category_id")
            ->map(function ($items, $id) {
                $cat = Category::find($id);
                return [
                    "label" => $cat->name ?? "Sem categoria",
                    "value" => $items->sum("amount"),
                ];
            })
            ->sortByDesc("value")
            ->take(5)
            ->values();

        return response()->json([
            "summary" => $summary,
            "data" => $group ?? [],
            "mini" => [
                "months" => $months,
                "incomeExpense" => [
                    [
                        "label" => "Receitas",
                        "data" => $incomeSeries
                    ],
                    [
                        "label" => "Despesas",
                        "data" => $expenseSeries
                    ]
                ],
                "top" => $top,
                "accounts" => [],
            ]
        ]);
    }
}
