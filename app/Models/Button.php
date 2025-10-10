<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Button extends Model
{
    use HasFactory;

    protected $table = 'murais_buttons';
    protected $fillable = [
        'painel_origin_id',
        'painel_destination_id',
        'configurations'
    ];
}
