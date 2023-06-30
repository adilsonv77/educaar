<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAccessActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'user_id',
        'timesAccessActivity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->belongsTo(Activity::class);
    }
}
