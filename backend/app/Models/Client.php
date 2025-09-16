<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'user_id',      // Chave estrangeira para User
        'cpf',
        'birth_date',
    ];

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
