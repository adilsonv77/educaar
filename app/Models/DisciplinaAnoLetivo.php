<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasCompositePrimaryKeyTrait;

class DisciplinaAnoLetivo extends Model
{

    use HasCompositePrimaryKeyTrait;

    protected $fillable = ['anoletivo_id', 'disciplina_id', 'bool_ativo'];

    protected $table = 'disciplinas_anos_letivos';

    protected $primaryKey = ['anoletivo_id', 'disciplina_id'];
    
    public $incrementing = false;

    public function disciplina() {
        return $this->belongsTo(Disciplina::class);
    }

    public function anoLetivo() {
        return $this->belongsTo(AnoLetivo::class);
    }


}