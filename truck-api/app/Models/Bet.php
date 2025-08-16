<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    protected $fillable = ['race_id', 'race_participant_id', 'odd_id', 'status'];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    // Relação corrigida para race_participant (snake_case)
    public function race_participant() // <--- MUDANÇA AQUI!
    {
        return $this->belongsTo(RaceParticipant::class);
    }

    public function odd()
    {
        return $this->belongsTo(Odd::class);
    }
}
