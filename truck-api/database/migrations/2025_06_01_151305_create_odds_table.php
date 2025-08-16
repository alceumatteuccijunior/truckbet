<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('odds', function (Blueprint $table) {
            $table->id();

            // Relaciona com o participante da corrida
            $table->foreignId('race_participant_id')
                ->constrained()
                ->onDelete('cascade');

            $table->decimal('valor_odd', 8, 2);
            $table->timestamp('data_atualizacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('odds');
    }
};
