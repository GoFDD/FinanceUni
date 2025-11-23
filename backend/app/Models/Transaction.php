<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'source',
        'category_id',
        'description',
        'amount',
        'date',
        'is_recurring',
        'external_id',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'date' => 'date'
    ];

    // ---------------- SCOPES ---------------- //

    /** Escopo para receitas */
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    /** Escopo para despesas */
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    /** Escopo filtrando pelo usuário autenticado */
    public function scopeFromUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

}
