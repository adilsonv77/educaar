<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scene extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'start_panel_id',
        'author_id',
        'disciplina_id',
    ];
}
