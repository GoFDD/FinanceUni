<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * GET /transactions?type=income|expense
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $type = $request->query('type');

        $query = Transaction::fromUser($userId)->orderBy('date', 'desc');

        if ($type === 'income') {
            $query->income();
        } elseif ($type === 'expense') {
            $query->expense();
        }

        return response()->json($query->get());
    }

    /**
     * POST /transactions — cria receita OU despesa
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $validated = $request->validate([
            'type'        => 'required|in:income,expense',
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric',
            'date'        => 'required|date',
            'category_id' => 'nullable|integer',
            'is_recurring'=> 'boolean',
            'notes'       => 'nullable|string'
        ]);

        // valida categoria
        if (!empty($validated['category_id'])) {
            $categoriaValida = Category::where('id', $validated['category_id'])
                ->where(function($q) use ($userId) {
                    $q->whereNull('user_id')->orWhere('user_id', $userId);
                })
                ->first();

            if (!$categoriaValida) {
                return response()->json([
                    'message' => 'Categoria inválida para este usuário.'
                ], 422);
            }
        }

        $validated['user_id'] = $userId;
        $validated['source'] = 'manual';

        $transaction = Transaction::create($validated);

        return response()->json($transaction, 201);
    }

    /**
     * PUT /transactions/{id}
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $transaction = Transaction::fromUser($userId)->findOrFail($id);

        $validated = $request->validate([
            'type'        => 'in:income,expense',
            'description' => 'string|max:255',
            'amount'      => 'numeric',
            'date'        => 'date',
            'category_id' => 'nullable|integer',
            'is_recurring'=> 'boolean',
            'notes'       => 'nullable|string'
        ]);

        // valida categoria
        if (!empty($validated['category_id'])) {
            $categoriaValida = Category::where('id', $validated['category_id'])
                ->where(function($q) use ($userId) {
                    $q->whereNull('user_id')->orWhere('user_id', $userId);
                })
                ->first();

            if (!$categoriaValida) {
                return response()->json([
                    'message' => 'Categoria inválida para este usuário.'
                ], 422);
            }
        }

        $transaction->update($validated);

        return response()->json($transaction);
    }

    /**
     * DELETE /transactions/{id}
     */
    public function destroy($id)
    {
        $userId = Auth::id();

        $transaction = Transaction::fromUser($userId)->findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transação excluída com sucesso']);
    }

    /**
     * Resumo das despesas p/ economia vs mês anterior
     */
    public function expenseSummary()
    {
        $userId = Auth::id();

        $mesAtual = now()->month;
        $mesPassado = now()->subMonth()->month;
        $anoAtual = now()->year;
        $anoPassado = now()->subMonth()->year;

        $atual = Transaction::expense()
            ->fromUser($userId)
            ->whereMonth('date', $mesAtual)
            ->whereYear('date', $anoAtual)
            ->sum('amount');

        $passado = Transaction::expense()
            ->fromUser($userId)
            ->whereMonth('date', $mesPassado)
            ->whereYear('date', $anoPassado)
            ->sum('amount');

        $economia = max(0, $passado - $atual);

        return response()->json([
            'despesa_mes_atual'   => $atual,
            'despesa_mes_passado' => $passado,
            'economia'            => $economia,
            'percentual'          => $passado > 0 ? round(($economia / $passado) * 100, 2) : 0
        ]);
    }
}
