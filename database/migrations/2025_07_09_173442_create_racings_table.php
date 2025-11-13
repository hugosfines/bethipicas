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
        Schema::create('racings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('calendar_id')->constrained()->cascadeOnDelete();

            $table->integer('race');

            $table->integer('total_horses');

            $table->dateTime('start_time')->nullable();

            $table->integer('distance')->nullable();

            $table->enum('status', ['open','close', 'result', 'inactive', 'canceled','suspended'])->default('inactive');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racings');
    }
};
