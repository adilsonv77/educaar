<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurmaModelo extends Model
{
    use HasFactory;
    
    protected $fillable= ['serie','school_id'];

    protected $table= 'turmas_modelos';

    public function school() {
        return $this->belongsTo(School::class);
    }


}
