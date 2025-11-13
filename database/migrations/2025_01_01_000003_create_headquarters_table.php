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
        Schema::create('headquarters', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('company_id')->unsigned()->nullable();

            $table->string('name');

            $table->string('email', 50)->nullable();

            $table->string('phone', 75)->nullable();

            $table->string('address')->nullable();

            $table->boolean('is_active')->default(false);

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('headquarters');
    }
};
