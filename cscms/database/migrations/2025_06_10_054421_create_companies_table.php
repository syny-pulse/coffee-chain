<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id('company_id');
            $table->string('company_name', 50);
            $table->string('email', 50)->unique();
            $table->enum('company_type', ['farmer', 'processor', 'retailer']);
            $table->string('phone', 20);
            $table->text('address');
            $table->string('registration_number', 50)->unique();
            $table->enum('acceptance_status', ['accepted', 'rejected', 'pending', 'visit_scheduled'])->default('pending');
            $table->string('pdf_path');
            $table->decimal('financial_risk_rating', 3, 1)->default(0.0);
            $table->decimal('reputational_risk_rating', 3, 1)->default(0.0);
            $table->decimal('compliance_risk_rating', 3, 1)->default(0.0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
};
