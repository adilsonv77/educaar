<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'content_id', 'glb', 'marcador', 'disciplina_id', 'professor_id', 'turma_id'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function contents()
    {
        return $this->belongsTo(Content::class);
    }

    public function grades()
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function access()
    {
        return $this->hasMany(StudentAccessActivity::class);
    }

    public function time()
    {
        return $this->hasMany(StudentTimeActivity::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
