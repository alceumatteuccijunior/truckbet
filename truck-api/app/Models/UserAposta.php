<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAposta extends Model
{
    protected $fillable = ['user_id', 'bet_id', 'valor_apostado', 'odd_usada', 'retorno_esperado'];

    public function bet()
    {
        return $this->belongsTo(Bet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}