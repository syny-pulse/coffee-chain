<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('processor_finished_goods_inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('processor_company_id');
            $table->unsignedBigInteger('recipe_id');
            $table->enum('product_name', ['drinking_coffee', 'roasted_coffee', 'coffee_scents', 'coffee_soap']);
            $table->string('product_variant', 100);
            $table->decimal('current_stock_units', 10, 2)->default(0);
            $table->decimal('reserved_stock_units', 10, 2)->default(0);
            $table->decimal('available_stock_units', 10, 2)->default(0);
            $table->decimal('production_cost_per_unit', 10, 2)->nullable();
            $table->decimal('selling_price_per_unit', 10, 2)->nullable();
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('processor_company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->foreign('recipe_id')->references('recipe_id')->on('product_recipes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('processor_finished_goods_inventory');
    }
};