<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';

    protected $fillable = ['disciplina_id', 'user_id'];

    public function disciplina() {
        return $this->belongsToMany(Disciplina::class);
    }

    public function user() {
        return $this->hasOne(User::class);
    }
}
