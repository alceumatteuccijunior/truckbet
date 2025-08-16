<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Chave estrangeira para a tabela users
            $table->string('pushinpay_transaction_id')->unique(); // ID da transação na PushinPay
            $table->decimal('amount', 10, 2); // Valor do depósito (em R$)
            $table->string('status')->default('pending'); // Status: pending, paid, failed, cancelled
            $table->text('pix_qrcode')->nullable(); // QR Code para Copia e Cola
            $table->text('pix_qrcode_base64')->nullable(); // QR Code em base64 para imagem
            $table->json('webhook_data')->nullable(); // Dados brutos do webhook para depuração/auditoria
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
