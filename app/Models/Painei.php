<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Painei extends Model
{
    use HasFactory;
    protected $table = 'paineis';
    protected $fillable = [
        'panel',
        'scene_id'
    ];
}
