<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('id_booking');
            $table->timestamp('date_hour')->default(DB::raw('CURRENT_TIMESTAMP')); 
            $table->integer('place_reserved');
            $table->string('state');
            $table->boolean('suitcase_authorized');
            $table->float('weight_authorized');
            $table->unsignedBigInteger('id_fly');
            $table->unsignedBigInteger('id_client');
    
            $table->foreign('id_fly')->references('id_fly')->on('flys')->onDelete('cascade');
            $table->foreign('id_client')->references('id_client')->on('clients')->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
