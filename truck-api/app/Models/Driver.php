<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'nome',
        'categoria',
        'marca',
        'numero_camiao',
        'cidade',
        'status',
    ];
}
