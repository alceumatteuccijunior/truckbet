<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaceParticipant extends Model
{
    protected $fillable = [
        'race_id',
        'driver_id',
    ];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function driver()
{
    return $this->belongsTo(\App\Models\Driver::class);
}
public function odds()
{
    return $this->hasMany(\App\Models\Odd::class, 'race_participant_id');
}
}
