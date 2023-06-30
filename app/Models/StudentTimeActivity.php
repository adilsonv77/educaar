<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTimeActivity extends Model
{
    use HasFactory;

    protected $fillable = ['timeEnterActivity', 'timeLeaveActivity', 'timeGeneral', 'activity_id', 'user_id'];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->belongsTo(Activity::class);
    }

}
