<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    public function edit(User $user)
{
    return view('admin.users.edit', compact('user'));
    
}

public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'cpf' => 'nullable|string',
        'saldo' => 'numeric|min:0',
        'status' => 'boolean',
        'role' => 'required|in:user,admin',
    ]);

    $user->update($data);

    return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado');
}

public function destroy(User $user)
{
    $user->delete();
    return redirect()->route('admin.users.index')->with('success', 'Usuário removido');
}

}
