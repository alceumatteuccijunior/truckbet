<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bet; // Certifique-se de que o modelo Bet está importado

class AvailableBetController extends Controller
{
    public function index()
    {
        $bets = Bet::with([
            'race:id,nome,status,data_hora',
            'race_participant.driver:id,nome', // <--- MUDANÇA AQUI: de 'raceParticipant' para 'race_participant'
            'odd:id,valor_odd'
        ])
        ->where('status', 'aberta')
        ->get();

        return response()->json($bets);
    }
}
