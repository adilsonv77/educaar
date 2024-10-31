<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'user_id',
        'activity_id',
        'alternative_answered',
        'answer',
        'correct'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id'); // Assumindo que 'user_id' é a chave estrangeira
    }

    public function question() // Alterado para belongsTo
    {
        return $this->belongsTo(Question::class, 'question_id'); // Uma resposta pertence a uma pergunta
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}
