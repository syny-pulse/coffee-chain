<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCompositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_composition', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->enum('coffee_breed', ['arabica', 'robusta']);
            $table->enum('roast_grade', ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5']);
            $table->decimal('percentage', 5, 2);
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('retailer_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_composition');
    }
}
