<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RandomSort extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content_id',
        'sort'
    ];
}
