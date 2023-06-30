<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'turma_modelo_id', 'school_id', 'ano_id'];

    protected $table = 'turmas';

    public function turmaModelo()
    {
        return $this->belongsTo(TurmaModelo::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
