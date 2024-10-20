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
        Schema::create('found_pet_galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('found_pet_id');
            $table->string('image');
            $table->timestamps();

            $table->foreign('found_pet_id')->references('id')->on('found_pets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('found_pet_galleries');
    }
};
