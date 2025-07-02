<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('farmer_orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('processor_company_id');
            $table->unsignedBigInteger('farmer_company_id');
            $table->unsignedBigInteger('processor_company_id')->nullable();
            $table->enum('coffee_variety', ['arabica', 'robusta']);
            $table->enum('processing_method', ['natural', 'washed', 'honey']);
            $table->enum('grade', ['grade_1', 'grade_2', 'grade_3', 'grade_4', 'grade_5']);
            $table->decimal('quantity_kg', 10, 2);
            $table->decimal('unit_price', 8, 2);
            $table->decimal('total_amount', 12, 2);
            $table->date('expected_delivery_date');
            $table->date('actual_delivery_date')->nullable();
            $table->enum('order_status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('processor_company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->foreign('farmer_company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->foreign('processor_company_id')->references('company_id')->on('companies')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('farmer_orders');
    }
};
