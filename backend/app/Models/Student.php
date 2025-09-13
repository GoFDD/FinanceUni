<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'user_id',      // Chave estrangeira para User
        'course',
        'student_id',
        'university',
    ];

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
