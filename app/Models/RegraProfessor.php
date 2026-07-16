<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegraProfessor extends Model
{
    use HasFactory;

    protected $fillable = [
        'regra_id',
        'professor_id',
    ];

    protected $table = 'regras_professores';

    public function regra()
    {
        return $this->belongsTo(Regras::class, 'regra_id');
    }

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
