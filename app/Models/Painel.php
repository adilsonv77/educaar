<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Painel extends Model
{
    use HasFactory;
    protected $table = 'murais_paineis';
    protected $fillable = [
        'panel',
        'panelnome',
        'mural_id'
    ];
}
