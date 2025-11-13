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
        Schema::create('bet_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bet_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('bet_type_id')->unsigned();
            $table->integer('race');
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_pay', 12, 2)->default(0.00);
            $table->decimal('amount_paid', 12, 2)->default(0.00);

            //$table->foreignId('racing_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('step_1')->nullable();
            $table->integer('step_2')->nullable();
            $table->integer('step_3')->nullable();
            $table->integer('step_4')->nullable();
            $table->integer('step_5')->nullable();
            $table->integer('step_6')->nullable();
            
            $table->enum('type', ['playing', 'expecting', 'win', 'return', 'lost', 'canceled'])->default('playing');
            $table->enum('status', ['pending', 'processed', 'paid'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bet_lines');
    }
};
