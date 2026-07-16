<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogo extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
    ];

    protected $table = 'jogos';

    public function content() {
        return $this->belongsTo(Content::class);
    }

    public function salas() {
        return $this->hasMany(Sala::class);
    }

}
