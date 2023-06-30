<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlunoTurma extends Model
{
    use HasFactory;

    protected $fillable = ['turma_id', 'aluno_id'];
    protected $table = 'alunos_turmas';

    public function aluno()
    {
        return $this->hasMany(Disciplina::class);
    }
    public function turma()
    {
        return $this->belongsTo(TurmaModelo::class);
    }
}
