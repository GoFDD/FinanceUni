<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /** Listar todas as receitas */
    public function index()
    {
        $userId = Auth::id();

        $transactions = Transaction::income()
            ->fromUser($userId)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($transactions);
    }

    /** Criar nova receita */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric',
            'date'        => 'required|date',
            'category_id' => 'nullable|integer',
            'is_recurring'=> 'boolean',
            'notes'       => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();
        $validated['type'] = 'income'; // importante!
        $validated['source'] = 'manual';

        $transaction = Transaction::create($validated);

        return response()->json($transaction, 201);
    }

    /** Mostrar receita isolada */
    public function show($id)
    {
        $userId = Auth::id();

        $transaction = Transaction::income()
            ->fromUser($userId)
            ->findOrFail($id);

        return response()->json($transaction);
    }

    /** Atualizar receita */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();

        $transaction = Transaction::income()
            ->fromUser($userId)
            ->findOrFail($id);

        $validated = $request->validate([
            'description' => 'string|max:255',
            'amount'      => 'numeric',
            'date'        => 'date',
            'category_id' => 'nullable|integer',
            'is_recurring'=> 'boolean',
            'notes'       => 'nullable|string'
        ]);

        $transaction->update($validated);

        return response()->json($transaction);
    }

    /** Delete */
    public function destroy($id)
    {
        $userId = Auth::id();

        $transaction = Transaction::income()
            ->fromUser($userId)
            ->findOrFail($id);

        $transaction->delete();

        return response()->json(['message' => 'Receita excluída com sucesso']);
    }
}
