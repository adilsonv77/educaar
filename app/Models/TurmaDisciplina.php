<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurmaDisciplina extends Model
{
    use HasFactory;
    
    protected $fillable = ['turma_id','disciplina_id','professor_id'];
    
    protected $table = 'turmas_disciplinas';

    public function disciplina()
    {
        return $this->hasMany(Disciplina::class);
    }  
    public function turmaodelo()
    {
        return $this->belongsTo(Turma::class);
    }
    public function professor()
    {
        return $this->hasMany(User::class);
    }  
}
