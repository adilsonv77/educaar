<?php

namespace App\Models;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'fechado', 'user_id', 'disciplina_id', 'turma_modelo_id', 'is_jogo', 'refeito'];

    public function jogo() {
        return $this->hasOne(Jogo::class);
    }

}


