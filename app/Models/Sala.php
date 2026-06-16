<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use HasFactory;

    protected $fillable = [
        'jogo_id',
        'regra_id',
        'turma_id',
        'nome',
        'aberta'
    ];

    protected $casts = [
        'aberta' => 'boolean',
        'started_at' => 'datetime',
    ];

    protected $table = 'salas';

    public function jogo()
    {
        return $this->belongsTo(Jogo::class);
    }

    public function regras()
    {
        return $this->belongsTo(Regras::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }
}
