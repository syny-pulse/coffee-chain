<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('coffee_variety');
            $table->string('grade');
            $table->decimal('unit_price', 12, 2);
            $table->timestamps();

            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->unique(['company_id', 'coffee_variety', 'grade']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricings');
    }
}; 