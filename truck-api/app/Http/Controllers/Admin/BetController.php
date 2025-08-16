<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bet;
use App\Models\Race; // Necessário para listar corridas no formulário
use App\Models\RaceParticipant; // Necessário para listar participantes no formulário
use App\Models\Odd; // Necessário para listar odds no formulário
use Carbon\Carbon; // Adicionar para formatação de data/hora se necessário

class BetController extends Controller
{
    public function index()
    {
        $bets = Bet::with(['race', 'race_participant.driver', 'odd'])->get(); 
        return view('admin.bets.index', compact('bets')); 
    }

    public function create()
    {
        // Verifique se estes modelos existem e estão funcionando
        $races = Race::all();
        $raceParticipants = RaceParticipant::with('driver')->get(); 
        $odds = Odd::all();

        return view('admin.bets.create', compact('races', 'raceParticipants', 'odds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'race_id' => 'required|exists:races,id',
            'race_participant_id' => 'required|exists:race_participants,id',
            'odd_id' => 'required|exists:odds,id',
            'status' => 'required|string|in:aberta,fechada,cancelada',
        ]);

        $bet = Bet::create($request->all());
        return redirect()->route('admin.bets.index')->with('success', 'Aposta criada com sucesso!');
    }
}