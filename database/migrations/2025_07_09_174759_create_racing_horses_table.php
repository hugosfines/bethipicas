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
        Schema::create('racing_horses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('racing_id')->constrained()->cascadeOnDelete();

            $table->integer('nro');

            $table->enum('status', ['run','scratch','invalid'])->default('run');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racing_horses');
    }
};
