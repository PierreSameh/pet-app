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
        Schema::table('found_pets', function (Blueprint $table) {
            $table->unsignedBigInteger('founder_id')->after('user_id')->nullable();

            $table->foreign('founder_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('found_pets', function (Blueprint $table) {
            $table->dropColumn('founder_id');
        });
    }
};
