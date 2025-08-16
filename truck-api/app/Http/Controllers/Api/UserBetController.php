<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bet;
use App\Models\User;
use App\Models\UserAposta; // Model que salva a aposta do usuário
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Para transações de banco de dados

class UserBetController extends Controller
{
    public function apostar(Request $request)
    {
        $request->validate([
            'bet_id' => 'required|exists:bets,id',
            'valor' => 'required|numeric|min:1',
            'race_id' => 'required|exists:races,id', // Validar race_id também
        ]);

        $user = Auth::user();
        $bet = Bet::with('odd')->findOrFail($request->bet_id);
        $valor = $request->valor;

        // Validar se a corrida está aberta antes de apostar
        if ($bet->race->status !== 'aberta') {
            return response()->json(['error' => 'Não é possível apostar em corridas fechadas.'], 400);
        }

        if ($user->saldo < $valor) {
            return response()->json(['error' => 'Saldo insuficiente.'], 403);
        }

        // REMOVIDO: A verificação para 'Você já apostou nessa opção de aposta.'
        // Comentado para permitir múltiplas apostas no mesmo bet_id.
        /*
        $jaApostou = UserAposta::where('user_id', $user->id)
                                ->where('bet_id', $bet->id)
                                ->exists();
        if ($jaApostou) {
            return response()->json(['error' => 'Você já apostou nessa opção de aposta.'], 409);
        }
        */

        DB::transaction(function () use ($user, $bet, $valor, $request) {
            // Subtrai o saldo
            $user->saldo -= $valor;
            $user->save(); // Salva o usuário com o saldo atualizado

            // Registra a aposta
            $user->userApostas()->create([
                'bet_id' => $bet->id,
                'race_id' => $request->race_id, // Salva o race_id na UserAposta
                'valor_apostado' => $valor,
                'odd_usada' => $bet->odd->valor_odd,
                'retorno_esperado' => $valor * $bet->odd->valor_odd,
                'status' => 'pendente', // Status inicial da aposta do usuário
            ]);
        });

        return response()->json(['message' => 'Aposta realizada com sucesso.']);
    }

    // Listar histórico de apostas do usuário logado
    public function index(Request $request)
    {
        $user = Auth::user();

        $userApostas = UserAposta::where('user_id', $user->id)
                                ->with([
                                    'bet.race',
                                    'bet.race_participant.driver'
                                ])
                                ->orderBy('created_at', 'desc')
                                ->get();

        return response()->json($userApostas);
    }
}
