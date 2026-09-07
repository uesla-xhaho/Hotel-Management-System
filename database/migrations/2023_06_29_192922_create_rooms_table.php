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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->Integer('number')->unique();
            $table->unsignedBigInteger('hotel_id');
            $table->string('category');
            $table->Integer('capacity');
            $table->float('price', 8, 2);
            $table->Integer('nrofbeds');
            $table->boolean('aircondition');
            $table->Integer('balcony');
            $table->string('image');
            $table->string('status');
            $table->timestamps();


            $table->index('hotel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
