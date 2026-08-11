<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regras extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'pontMax',
        'tempo',
        'data_inicio',
        'data_limite'
    ];

    protected $table = 'regras';
}
