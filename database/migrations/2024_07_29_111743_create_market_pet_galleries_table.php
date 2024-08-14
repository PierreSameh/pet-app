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
        Schema::create('market_pet_galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('market_pet_id');
            $table->string('image');
            $table->timestamps();

            $table->foreign('market_pet_id')->references('id')->on('market_pets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_pet_galleries');
    }
};
