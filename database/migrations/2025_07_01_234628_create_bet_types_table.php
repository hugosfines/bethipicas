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
        Schema::create('bet_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();

            $table->bigInteger('category_id')->unsigned()->nullable();
            
            $table->text('description')->nullable();

            $table->integer('follow')->unsigned()->default(1);

            $table->json('positions')->nullable();

            $table->enum('box', ['yes', 'no'])->default('no');

            $table->boolean('is_active')->default(true);

            // Foreign key constraint for category_id
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bet_types');
    }
};
