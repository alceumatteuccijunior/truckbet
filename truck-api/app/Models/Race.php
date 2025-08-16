<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    protected $fillable = [
        'nome',
        'circuito',
        'cidade',
        'estado',
        'data_hora',
        'status'
    ];

public function participants()
{
    return $this->hasMany(\App\Models\RaceParticipant::class);
}



}
