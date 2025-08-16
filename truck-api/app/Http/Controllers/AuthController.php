<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // <-- Adicionado para logs

class AuthController extends Controller // <-- CORRIGIDO: de 'Controllear' para 'Controller'
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed',
            'status' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'status' => $fields['status'] ?? true,
        ]);

        $token = $user->createToken('apitoken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('apitoken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Deslogado com sucesso']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function adicionarSaldo(Request $request, $id)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0.01'
        ]);

        $user = User::findOrFail($id);
        $user->saldo += $request->valor;
        $user->save();

        return response()->json([
            'message' => 'Saldo adicionado com sucesso!',
            'saldo' => $user->saldo
        ]);
    }

    /**
     * NOVO MÉTODO: Atualiza o CPF do usuário logado.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCpf(Request $request)
    {
        $user = Auth::user(); // Obtém o usuário autenticado
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        // Valida o CPF: obrigatório, string, 11 dígitos, apenas números
        $request->validate([
            'cpf' => 'required|string|digits:11', // `digits:11` valida que são 11 dígitos e são numéricos
        ]);

        try {
            $user->cpf = $request->cpf; // Atualiza o CPF
            $user->save(); // Salva no banco de dados

            return response()->json(['message' => 'CPF atualizado com sucesso!'], 200);

        } catch (\Exception $e) {
            // Loga qualquer erro inesperado e retorna 500
            Log::error("Erro ao atualizar CPF para usuário {$user->id}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Erro interno ao salvar CPF. Tente novamente.'], 500);
        }
    }
}
