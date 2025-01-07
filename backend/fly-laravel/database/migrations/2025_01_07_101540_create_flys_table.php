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
        Schema::create('flys', function (Blueprint $table) {
            $table->id('id_fly');
            $table->dateTime('date_hour_landing');
            $table->dateTime('date_hour_fly_off');
            $table->string('state');
            $table->unsignedBigInteger('id_plane');
            $table->unsignedBigInteger('id_airport_landing');
            $table->unsignedBigInteger('id_airport_fly_off');
    
            $table->foreign('id_plane')->references('id_plane')->on('planes')->onDelete('cascade');
            $table->foreign('id_airport_landing')->references('id_airport')->on('airports')->onDelete('cascade');
            $table->foreign('id_airport_fly_off')->references('id_airport')->on('airports')->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flys');
    }
};
