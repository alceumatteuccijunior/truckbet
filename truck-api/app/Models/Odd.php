<?php

namespace App\Models;
use App\Models\BetType;
use Illuminate\Database\Eloquent\Model;

class Odd extends Model
{
   protected $fillable = [
    'race_id',
    'driver_id',
    'race_participant_id',
    'valor_odd',
    'bet_type_id',
    'data_atualizacao'
];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function raceParticipant()
    {
        return $this->belongsTo(RaceParticipant::class, 'race_participant_id');
    }
   public function betType()
    {
        return $this->belongsTo(BetType::class, 'bet_type_id');
    }
}
