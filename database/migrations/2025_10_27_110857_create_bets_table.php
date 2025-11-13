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
        Schema::create('bets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('racing_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique(); // Código único
            $table->string('custom_code'); // Tu código personalizado de 4-5 caracteres
            $table->date('date_at'); // Fecha del ticket
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('amount', 10, 2);
            
            $table->enum('type', ['playing', 'win', 'lost', 'canceled'])->default('playing');
            $table->enum('status', ['pending', 'processed', 'paid'])->default('pending');

            $table->timestamps();

            $table->unique(['custom_code', 'date_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
