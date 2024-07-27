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
        Schema::create('lost_pet_galleries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lostpet_id');
            $table->string('image');
            $table->timestamps();

            $table->foreign('lostpet_id')->references('id')->on('lost_pets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_pet_galleries');
    }
};
