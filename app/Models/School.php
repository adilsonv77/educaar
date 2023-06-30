<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'qr_letra', 'qr_numero'];
    protected $table = 'schools';


    public function users() {
        return $this->hasMany(User::class);
    }

    public function disciplinas() {
        return $this->hasMany(Disciplina::class);
    }
    
}
