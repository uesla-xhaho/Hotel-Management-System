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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->Integer("price");
            $table->string("method");
            $table->string("status");
            $table->Integer("duration");
            
            $table->unsignedBigInteger('booking_id');
            $table->index('booking_id');
            
            $table->unsignedBigInteger('customer_id');
            $table->index('customer_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
