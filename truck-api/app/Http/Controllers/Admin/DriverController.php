<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'numero_camiao' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'status' => 'required|string|in:ativo,inativo',
        ]);

        Driver::create($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Motorista cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'numero_camiao' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'status' => 'required|string|in:ativo,inativo',
        ]);

        $driver = Driver::findOrFail($id);
        $driver->update($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Motorista atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return redirect()->route('admin.drivers.index')->with('success', 'Motorista excluído com sucesso!');
    }
}
