<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regras extends Model
{
    use HasFactory;

    protected $fillable = [
        'pontMax',
        'tempo',
    ];

    protected $table = 'regras';
}
