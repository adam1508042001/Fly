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
        Schema::create('runways', function (Blueprint $table) {
            $table->id('id_runway');
            $table->string('state');
            $table->integer('size');
            $table->unsignedBigInteger('id_airport');
    
            $table->foreign('id_airport')->references('id_airport')->on('airports')->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('runways');
    }
};
