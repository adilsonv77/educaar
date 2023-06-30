<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCompositePrimaryKeyTrait;

class DisciplinaProfessor extends Model
{
    use HasCompositePrimaryKeyTrait;

    protected $fillable = ['bool_ativo', 'disciplina_id', 'professor_id', 'anoletivo_id'];

    protected $table = 'disciplinas_professores';
    protected $primaryKey = ['disciplina_id', 'professor_id', 'anoletivo_id'];
    
    public $incrementing = false;

    public function disciplinas() {
        return $this->belongsTo(Disciplina::class);
    }

    public function professores() {
        return $this->belongsTo(User::class);
    }


}