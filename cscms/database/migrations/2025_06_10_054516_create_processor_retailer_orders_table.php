<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessorRetailerOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('processor_retailer_orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('order_number', 20)->unique();
            $table->unsignedBigInteger('processor_company_id');
            $table->unsignedBigInteger('retailer_company_id');
            $table->decimal('total_amount', 12, 2);
            $table->date('expected_delivery_date');
            $table->date('actual_delivery_date')->nullable();
            $table->enum('order_status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('processor_company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->foreign('retailer_company_id')->references('company_id')->on('companies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('processor_retailer_orders');
    }
} 