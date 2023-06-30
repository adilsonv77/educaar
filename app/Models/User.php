<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'school_id',
        'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function access()
    {
        return $this->hasMany(StudentAccessActivity::class);
    }

    public function activities() {
        return $this->belongsToMany(Activity::class);
    }

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function matriculas() {
        return $this->hasMany(Matricula::class);
    }
}
