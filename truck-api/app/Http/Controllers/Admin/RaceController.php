<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Race;

class RaceController extends Controller
{
    /**
     * Exibe a lista de corridas.
     */
    public function index()
    {
        $races = Race::all();
        return view('admin.races.index', compact('races'));
    }

    /**
     * Exibe o formulário de criação.
     */
    public function create()
    {
        return view('admin.races.create');
    }

    /**
     * Salva uma nova corrida no banco.
     */
 public function store(Request $request)
{
    $validated = $request->validate([
        'nome' => 'required|string|max:255',
        'circuito' => 'required|string|max:255',
        'cidade' => 'required|string|max:255',
        'estado' => 'required|string|max:255',
        'data_hora' => 'required|date',
        'status' => 'required|string|in:aberta,fechada',
    ]);

    Race::create($validated);

    return redirect()->route('admin.races.index')->with('success', 'Corrida criada com sucesso!');
}

    /**
     * Exibe o formulário de edição.
     */
    public function edit($id)
    {
        $race = Race::findOrFail($id);
        return view('admin.races.edit', compact('race'));
    }

    /**
     * Atualiza uma corrida existente.
     */
   public function update(Request $request, $id)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'circuito' => 'required|string|max:255',
        'cidade' => 'required|string|max:255',
        'estado' => 'required|string|max:255',
        'data_hora' => 'required|date',
        'status' => 'required|string|in:aberta,fechada',
    ]);

    $race = Race::findOrFail($id);
    $race->update($request->all());

    return redirect()->route('admin.races.index')->with('success', 'Corrida atualizada com sucesso!');
}

    /**
     * Remove uma corrida do banco.
     */
    public function destroy($id)
    {
        $race = Race::findOrFail($id);
        $race->delete();

        return redirect()->route('admin.races.index')
            ->with('success', 'Corrida deletada com sucesso!');
    }
public function show($id)
{
    $race = Race::with('participants.driver')->findOrFail($id);
    return view('admin.races.show', compact('race'));
}





}
