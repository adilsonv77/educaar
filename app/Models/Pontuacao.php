<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pontuacao extends Model
{
    use HasFactory;

    protected $table = 'pontuacoes';
    protected $fillable = [
        'user_id',
        'activity_id',
        'pontuacao'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function activity() {
        return $this->belongsTo(Activity::class, 'activity_id');
    }
}
