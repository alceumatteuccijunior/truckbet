<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Race;
use App\Models\RaceParticipant;
use App\Models\Odd;
use App\Models\BetType;

class OddController extends Controller
{
   public function selectRace()
{
    $races = Race::with('participants.driver')->get();
    return view('admin.odds.select_race', compact('races'));
}
    public function create(Race $race)
    {
        $participants = $race->participants()->with('driver')->get();
        $bettypes = BetType::all();
        return view('admin.odds.create', compact('race', 'participants', 'bettypes'));
    }

    public function store(Request $request, Race $race)
{
    foreach ($request->input('odds', []) as $participantId => $types) {
        foreach ($types as $typeId => $oddData) {
            if (
                isset($oddData['valor_odd'], $oddData['bet_type_id']) &&
                $oddData['valor_odd'] !== '' &&
                $oddData['bet_type_id'] !== ''
            ) {
                Odd::updateOrCreate(
                    [
                        'race_participant_id' => $participantId,
                        'bet_type_id' => $oddData['bet_type_id'],
                    ],
                    [
                        'valor_odd' => $oddData['valor_odd'],
                    ]
                );
            }
        }
    }

    return redirect()->route('admin.odds.selectRace')->with('success', 'Odds atualizadas com sucesso.');
}

    public function show(Race $race)
{
    $participants = $race->participants()
        ->with(['driver', 'odds.betType']) // eager load
        ->get();
    $bettypes = BetType::all();
    return view('admin.odds.show', compact('race', 'participants', 'bettypes'));
}

}
