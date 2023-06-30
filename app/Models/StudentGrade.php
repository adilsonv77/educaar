<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'user_id',
        'correctQuestions',
        'wrongQuestions',
        'numberQuestions'
    ];

    public function activities()
    {
        return $this->belongsTo(Activity::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
