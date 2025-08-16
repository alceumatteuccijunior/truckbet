<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    const WITHDRAWAL_FEE = 1.00;
    const MIN_WITHDRAWAL_AMOUNT = 5.00;

    // ... (Seu código existente para requestWithdrawal) ...
    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:' . (self::MIN_WITHDRAWAL_AMOUNT),
            'pix_key' => 'required|string|min:11|max:14',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        if (empty($user->cpf)) {
            return response()->json(['error' => 'Seu CPF não está cadastrado. Por favor, atualize seu perfil para solicitar saques.'], 400);
        }
        if ($user->cpf !== $request->pix_key) {
            return response()->json(['error' => 'A chave PIX informada deve ser o seu CPF cadastrado. Por favor, verifique.'], 400);
        }

        $requestedAmount = $request->amount;
        $amountToReceive = $requestedAmount - self::WITHDRAWAL_FEE;

        if ($user->saldo < $requestedAmount) {
            return response()->json(['error' => 'Saldo insuficiente para a operação (você solicitou R$ ' . number_format($requestedAmount, 2, ',', '.') . ', incluindo taxa de R$ ' . number_format(self::WITHDRAWAL_FEE, 2, ',', '.') . ').'], 403);
        }
        if ($amountToReceive <= 0) {
             return response()->json(['error' => 'O valor do saque, após a taxa de R$ ' . number_format(self::WITHDRAWAL_FEE, 2, ',', '.') . ', deve ser maior que R$ 0,00.'], 400);
        }
        if ($requestedAmount < self::MIN_WITHDRAWAL_AMOUNT) {
            return response()->json(['error' => 'O valor mínimo para saque é de R$ ' . number_format(self::MIN_WITHDRAWAL_AMOUNT, 2, ',', '.') . '.'], 400);
        }

        $lastWithdrawal = Withdrawal::where('user_id', $user->id)
                                    ->where('created_at', '>=', Carbon::now()->subDay())
                                    ->orderBy('created_at', 'desc')
                                    ->first();

        if ($lastWithdrawal) {
            if ($lastWithdrawal->status === 'pending' || $lastWithdrawal->status === 'approved') {
                return response()->json(['error' => 'Você pode solicitar apenas 1 saque a cada 24 horas. Por favor, aguarde para fazer uma nova solicitação ou entre em contato com o suporte.'], 429);
            }
        }

        try {
            DB::transaction(function () use ($user, $requestedAmount, $amountToReceive, $request) {
                $user->saldo -= $requestedAmount; 
                $user->save();

                Withdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $requestedAmount,
                    'status' => 'pending',
                    'payment_details' => json_encode([
                        'pix_key_type' => 'CPF',
                        'pix_key' => $request->pix_key,
                        'amount_received_by_user' => $amountToReceive,
                        'fee_applied' => self::WITHDRAWAL_FEE,
                    ]),
                    'processed_at' => null,
                ]);
            });

            return response()->json([
                'message' => 'Solicitação de saque de R$ ' . number_format($requestedAmount, 2, ',', '.') . ' enviada com sucesso! Você receberá R$ ' . number_format($amountToReceive, 2, ',', '.') . ' (taxa de R$ ' . number_format(self::WITHDRAWAL_FEE, 2, ',', '.') . ' aplicada).',
                'amount_requested' => $requestedAmount,
                'amount_received' => $amountToReceive,
                'fee' => self::WITHDRAWAL_FEE,
                'processing_time_info' => 'O saque pode levar até 120 horas (5 dias) após a aprovação para ser recebido, devido ao fluxo de pedidos.'
            ], 200);

        } catch (\Exception $e) {
            Log::error("Erro ao solicitar saque para usuário {$user->id}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response()->json(['error' => 'Erro interno ao processar sua solicitação de saque.'], 500);
        }
    }

    /**
     * Lista o histórico de saques do usuário logado.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $withdrawals = Withdrawal::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->get();
                                
        return response()->json($withdrawals);
    }
}
