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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->Integer('guests');
            $table->date('checkin');
            $table->date('checkout');
            $table->string('status');
            $table->string('comment');
            $table->timestamps();

            $table->unsignedBigInteger('room_id');
            $table->index('room_id');
            
            $table->unsignedBigInteger('customer_id');
            $table->index('customer_id');
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
