<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnoLetivo extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bool_atual', 'school_id'];

    protected $table = 'anos_letivos';

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function disciplinas() {
        return $this->hasMany(DisciplinaAnoLetivo::class);
    }
}
