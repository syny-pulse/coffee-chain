<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('retailer_order_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('recipe_id');
            $table->enum('product_name', ['drinking_coffee', 'roasted_coffee', 'coffee_scents', 'coffee_soap']);
            $table->string('product_variant', 100);
            $table->decimal('quantity_units', 10, 2);
            $table->decimal('unit_price', 8, 2);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('retailer_orders')->onDelete('cascade');
            $table->foreign('recipe_id')->references('recipe_id')->on('product_recipes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('retailer_order_items');
    }
};