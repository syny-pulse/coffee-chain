<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('farmer_harvest', function (Blueprint $table) {
            $table->id('harvest_id');
            $table->unsignedBigInteger('company_id');
            $table->enum('coffee_variety', ['arabica', 'robusta']);
            $table->enum('processing_method', ['natural', 'washed', 'honey']);
            $table->enum('grade', ['grade_1', 'grade_2', 'grade_3', 'grade_4', 'grade_5']);
            $table->decimal('quantity_kg', 10, 2);
            $table->decimal('available_quantity_kg', 10, 2);
            $table->date('harvest_date');
            $table->enum('availability_status', ['available', 'reserved', 'sold_out', 'expired'])->default('available');
            $table->text('quality_notes')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('farmer_harvest');
    }
};