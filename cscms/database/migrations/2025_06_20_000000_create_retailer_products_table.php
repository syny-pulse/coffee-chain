<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retailer_products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('product_name');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->string('variant')->nullable();
            $table->text('characteristics')->nullable();
            $table->string('image')->nullable();
            $table->text('marketing_description')->nullable();
            $table->json('performance_metrics')->nullable();
            $table->timestamps(); // includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('retailer_products');
    }
}
