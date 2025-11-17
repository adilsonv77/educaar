<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'content_id',
        'next_position',
    ];

    protected $table = 'ar_progress';
}
