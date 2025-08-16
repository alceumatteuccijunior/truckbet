<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AvailableBetController;
use App\Http\Controllers\Api\UserBetController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\WithdrawalController;
use App\Models\Race;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
Route::middleware('auth:sanctum')->post('/users/{id}/adicionar-saldo', [AuthController::class, 'adicionarSaldo']);

Route::get('/races/open', function () {
    return Race::where('status', 'aberta')
        ->orderBy('data_hora')
        ->get();
});

Route::get('/bets', [AvailableBetController::class, 'index']);

Route::middleware('auth:sanctum')->post('/apostar', [UserBetController::class, 'apostar']);
Route::middleware('auth:sanctum')->get('/user-bets', [UserBetController::class, 'index']);


// ROTAS PARA DEPÓSITO VIA PUSHINPAY
Route::middleware('auth:sanctum')->post('/deposit-pix', [DepositController::class, 'initiatePixDeposit']);
Route::post('/pushinpay-webhook', [DepositController::class, 'handlePushinpayWebhook']);

// ROTAS PARA A CARTEIRA
// Listar histórico de depósitos do usuário
Route::middleware('auth:sanctum')->get('/me/deposits', [DepositController::class, 'index']); 
// Listar histórico de saques do usuário
Route::middleware('auth:sanctum')->get('/user-withdrawals', [WithdrawalController::class, 'index']); 

// Rota para solicitar saque (POST)
Route::middleware('auth:sanctum')->post('/withdraw-request', [WithdrawalController::class, 'requestWithdrawal']);

// NOVA ROTA: Atualizar CPF do usuário logado
Route::middleware('auth:sanctum')->post('/me/update-cpf', [AuthController::class, 'updateCpf']); // <-- NOVA ROTA

