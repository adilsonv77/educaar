<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaTurmaModelo extends Model
{
    use HasFactory;

    protected $fillable = ['disciplina_id','turma_modelo_id'];
    
    protected $table = 'disciplinas_turmas_modelos';

    public function disciplina()
    {
        return $this->hasMany(Disciplina::class);
    }  
    public function TurmaModelo()
    {
        return $this->belongsTo(TurmaModelo::class);
    }
}
