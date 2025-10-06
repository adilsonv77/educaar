<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mural extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'start_painel_id',
        'author_id',
        'disciplina_id',
        'canvasTop',
        'canvasLeft',
        'centroTop',
        'centroLeft',
        'scale',
    ];
    protected $table = 'murais';

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }
}
