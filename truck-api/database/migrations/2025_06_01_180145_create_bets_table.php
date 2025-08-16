<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_id')->constrained()->onDelete('cascade');
            $table->foreignId('race_participant_id')->constrained()->onDelete('cascade');
            $table->foreignId('odd_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('aberta');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('bets');
    }
};

