<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->unsignedBigInteger('processor_company_id');
            $table->string('employee_name', 100);
            $table->string('employee_code', 30)->unique();
            $table->enum('skill_set', ['grading', 'roasting', 'packaging', 'logistics', 'quality_control', 'maintenance']);
            $table->enum('primary_station', ['grading', 'roasting', 'packaging', 'logistics', 'quality_control', 'maintenance']);
            $table->enum('current_station', ['grading', 'roasting', 'packaging', 'logistics', 'quality_control', 'maintenance'])->nullable();
            $table->enum('availability_status', ['available', 'busy', 'on_break', 'off_duty', 'on_leave'])->default('available');
            $table->enum('shift_schedule', ['morning', 'afternoon', 'night', 'flexible'])->default('morning');
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->timestamps();

            $table->foreign('processor_company_id')->references('company_id')->on('companies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};