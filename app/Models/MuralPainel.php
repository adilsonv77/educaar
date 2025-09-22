<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuralPainel extends Model
{
    use HasFactory;
    
    protected $table = 'murais_paineis';
    protected $fillable = [
        'id',
        'panelnome',
        'panel',
        'mural_id'
    ];

}