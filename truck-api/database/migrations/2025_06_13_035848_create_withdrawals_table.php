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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Chave estrangeira para a tabela users
            $table->decimal('amount', 10, 2); // Valor do saque (em R$) - valor total solicitado
            $table->string('status')->default('pending'); // Status: pending, approved, rejected, cancelled
            $table->json('payment_details')->nullable(); // Detalhes da conta para saque (ex: chave Pix, valor a receber, taxa)
            $table->timestamp('processed_at')->nullable(); // Data em que o saque foi processado/enviado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
