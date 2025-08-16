<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DepositController extends Controller
{
    // ... (Seu código existente para initiatePixDeposit) ...
    const WITHDRAWAL_FEE = 1.00; // Taxa fixa de R$1.00 por saque
    const MIN_WITHDRAWAL_AMOUNT = 5.00; 
    
    public function initiatePixDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:' . (self::MIN_WITHDRAWAL_AMOUNT), 
        ]);

        $user = Auth::user(); 
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $requestedAmount = $request->amount;
        $amountInCents = round($request->amount * 100);

        $pushinpayApiUrl = env('PUSHINPAY_API_URL');
        $pushinpayBearerToken = env('PUSHINPAY_BEARER_TOKEN');
        
        $webhookUrl = config('app.url') . '/truck-api/public/api/pushinpay-webhook'; 

        if (!$pushinpayBearerToken || !$pushinpayApiUrl) {
            Log::error('PUSHINPAY_API_URL ou PUSHINPAY_BEARER_TOKEN não configurados no .env');
            return response()->json(['error' => 'Configuração da API de pagamento incompleta. Contate o suporte.'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $pushinpayBearerToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post("{$pushinpayApiUrl}/api/pix/cashIn", [
                'value' => $amountInCents,
                'webhook_url' => $webhookUrl,
                'external_id' => 'user-' . $user->id . '-deposit-' . uniqid(), 
                'buyer_name' => $user->name,
                'buyer_email' => $user->email,
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                $deposit = Deposit::create([
                    'user_id' => $user->id,
                    'pushinpay_transaction_id' => $responseData['id'],
                    'amount' => $request->amount,
                    'status' => 'pending',
                    'pix_qrcode' => $responseData['qr_code'] ?? null,
                    'pix_qrcode_base64' => $responseData['qr_code_base64'] ?? null,
                ]);

                return response()->json([
                    'message' => 'Pagamento PIX gerado com sucesso!',
                    'deposit_id' => $deposit->id,
                    'pushinpay_id' => $responseData['id'],
                    'qr_code' => $responseData['qr_code'],
                    'qr_code_base64' => $responseData['qr_code_base64'],
                    'amount' => $request->amount,
                ], 200);

            } else {
                Log::error("Erro PushinPay initiatePixDeposit: " . $response->status() . " - " . json_encode($responseData));
                return response()->json([
                    'error' => 'Falha ao gerar pagamento PIX.',
                    'details' => $responseData['message'] ?? 'Erro desconhecido.'
                ], $response->status());
            }

        } catch (\Exception $e) {
            Log::error("Exceção ao chamar PushinPay: " . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao processar pagamento.'], 500);
        }
    }

    public function handlePushinpayWebhook(Request $request)
    {
        // ... (Seu código existente para handlePushinpayWebhook) ...
        $payload = $request->all();
        Log::info('Webhook PushinPay recebido:', $payload);

        $transactionId = $payload['id'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$transactionId || !$status) {
            Log::warning('Webhook PushinPay: Payload inválido ou faltando ID/status.', $payload);
            return response('Bad Request: Missing ID or status', 400);
        }

        try {
            DB::transaction(function () use ($transactionId, $status, $payload) {
                $deposit = Deposit::where('pushinpay_transaction_id', $transactionId)->first();

                if (!$deposit) {
                    Log::warning("Webhook PushinPay: Depósito não encontrado para ID: {$transactionId}. Ignorando.");
                    return; 
                }

                $deposit->status = $status;
                $deposit->webhook_data = json_encode($payload);
                $deposit->save();

                if ($status === 'paid' && $deposit->user->saldo_updated_by_webhook !== $deposit->pushinpay_transaction_id) {
                    $user = $deposit->user;
                    $user->saldo += $deposit->amount;
                    $user->saldo_updated_by_webhook = $transactionId;
                    $user->save();
                    Log::info("Saldo do usuário {$user->id} atualizado em {$deposit->amount}. Novo saldo: {$user->saldo}. Transação PushinPay ID: {$transactionId}");
                } else if ($status === 'paid' && $deposit->user->saldo_updated_by_webhook === $deposit->pushinpay_transaction_id) {
                    Log::info("Webhook PushinPay: Pagamento {$transactionId} já processado e saldo atualizado. Ignorando reenvio.");
                }
            });

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error("Erro ao processar webhook PushinPay para ID: {$transactionId} - " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return response('Internal Server Error', 500);
        }
    }


    /**
     * NOVO MÉTODO: Lista o histórico de depósitos do usuário logado.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) // Pode usar index para listar, se a rota for tipo Route::resource
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        // Carrega os depósitos do usuário
        $deposits = Deposit::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                                
        return response()->json($deposits);
    }
}
