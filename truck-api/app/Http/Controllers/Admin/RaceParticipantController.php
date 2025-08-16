<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Race;
use App\Models\RaceParticipant;

class RaceParticipantController extends Controller
{
public function selectRace()
{
    $races = \App\Models\Race::all();
    return view('admin.race_participants.select_race', compact('races'));
}

    
    public function index(Race $race)
    {
        $participants = $race->participants()->with('driver')->get();
        return view('admin.race_participants.index', compact('participants', 'race'));
    }

public function create(Race $race)
{
    if ($race->status === 'fechada') {
        return redirect()->route('admin.races.participants.selectRace')
            ->with('error', 'Não é possível adicionar participantes a uma corrida fechada.');
    }

    $drivers = \App\Models\Driver::all();
    return view('admin.races.participants.create', compact('race', 'drivers'));
}

public function store(Request $request, Race $race)
{
    if ($race->status === 'fechada') {
        return redirect()->route('admin.races.participants.selectRace')
            ->with('error', 'Corrida fechada! Não é possível adicionar participantes.');
    }

    $validated = $request->validate([
        'driver_id' => 'required|exists:drivers,id',
    ]);

    // Segurança extra: só permite piloto ativo
    $driver = \App\Models\Driver::findOrFail($validated['driver_id']);

    if ($driver->status !== 'ativo') {
        return redirect()->back()->with('error', 'Piloto inativo não pode ser adicionado.');
    }

    // Verificação se o piloto já está na corrida
    $alreadyExists = $race->participants()
        ->where('driver_id', $validated['driver_id'])
        ->exists();

    if ($alreadyExists) {
        return redirect()->back()->with('error', 'Esse piloto já está adicionado à corrida.');
    }

    // Criação segura
    $race->participants()->create($validated);

    return redirect()->route('admin.races.participants.index', $race->id)
        ->with('success', 'Participante adicionado com sucesso!');
}


    public function destroy(Race $race, $participantId)
{
     $participant = $race->participants()->findOrFail($participantId);
    $participant->delete();

    return redirect()->route('admin.races.show', $race->id)
        ->with('success', 'Participante removido com sucesso.');
        
}





}
