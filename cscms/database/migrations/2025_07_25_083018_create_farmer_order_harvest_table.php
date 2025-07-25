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
        Schema::create('farmer_order_harvest', function (Blueprint $table) {
            $table->id('allocation_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('harvest_id');
            $table->decimal('allocated_quantity_kg', 10, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('farmer_orders')->onDelete('cascade');
            $table->foreign('harvest_id')->references('harvest_id')->on('farmer_harvest')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_order_harvest');
    }
};
