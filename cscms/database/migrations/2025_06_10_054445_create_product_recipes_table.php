<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_recipes', function (Blueprint $table) {
            $table->id('recipe_id');
            $table->enum('product_name', ['drinking_coffee', 'roasted_coffee', 'coffee_scents', 'coffee_soap']);
            $table->string('recipe_name', 100);
            $table->enum('coffee_variety', ['arabica', 'robusta']);
            $table->enum('processing_method', ['natural', 'washed', 'honey']);
            $table->enum('required_grade', ['grade_1', 'grade_2', 'grade_3', 'grade_4', 'grade_5']);
            $table->decimal('percentage_composition', 5, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_recipes');
    }
};