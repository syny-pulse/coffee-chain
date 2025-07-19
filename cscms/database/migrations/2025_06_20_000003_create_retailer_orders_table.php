<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailerOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retailer_order', function (Blueprint $table) {
            $table->id();
            $table->enum('product_type', ['drinking_coffee', 'roasted_coffee', 'coffee_scents', 'coffee_soap']);
            $table->enum('coffee_breed', ['arabica', 'robusta']);
            $table->enum('roast_grade', ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5']);
            $table->integer('quantity');
            $table->string('order_status');
            $table->string('order_month')->nullable();
            $table->integer('predicted_demand')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->boolean('delivery_confirmation')->default(false);
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
        Schema::dropIfExists('retailer_order');
    }
}
