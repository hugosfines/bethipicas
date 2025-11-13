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
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('racing_id')->constrained()->cascadeOnDelete()->unique();
            $table->bigInteger('bet_type_id')->unsigned();
            $table->integer('number')->unsigned();
            $table->decimal('dividendo', 10, 2);
            $table->bigInteger('user_id')->unsigned();
            $table->integer('order')->unsigned();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
