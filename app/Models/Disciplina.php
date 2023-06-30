<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'school_id', 'is_checked'];

    protected $table = 'disciplinas';

    public function contents() {
        return $this->hasMany(Content::class);
    }

    public function matricula() {
        return $this->hasMany(Matricula::class);
    }

    public function school() {
        return $this->belongsTo(School::class);
    }
    
}
