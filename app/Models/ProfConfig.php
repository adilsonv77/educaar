<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfConfig extends Model
{
    use HasFactory;
    
    protected $fillable = ['anoletivo_id', 'turma_id','disciplina_id','professor_id', 'dt_corte'];
    
    protected $table = 'prof_config';
}
