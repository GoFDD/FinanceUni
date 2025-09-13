<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $table = 'universities';

    protected $fillable = [
        'user_id',      // Chave estrangeira para User
        'university_name',
        'cnpj',
        'address',
    ];

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
