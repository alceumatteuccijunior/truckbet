<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BetType;

class BetTypeController extends Controller
{
    public function index()
    {
        $bettypes = BetType::all();
        return view('admin.bettypes.index', compact('bettypes'));
    }

    public function create()
    {
        return view('admin.bettypes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        BetType::create($request->all());

        return redirect()->route('admin.bettypes.index')
                         ->with('success', 'Tipo de aposta criado com sucesso!');
    }

    public function show(BetType $bettype)
    {
        return view('admin.bettypes.show', compact('bettype'));
    }

    public function edit(BetType $bettype)
    {
        return view('admin.bettypes.edit', compact('bettype'));
    }

    public function update(Request $request, BetType $bettype)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $bettype->update($request->all());

        return redirect()->route('admin.bettypes.index')
                         ->with('success', 'Tipo de aposta atualizado com sucesso!');
    }

    public function destroy(BetType $bettype)
    {
        $bettype->delete();

        return redirect()->route('admin.bettypes.index')
                         ->with('success', 'Tipo de aposta excluído com sucesso!');
    }
}