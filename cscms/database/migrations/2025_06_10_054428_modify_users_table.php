<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['farmer', 'processor', 'retailer', 'admin'])->after('email');
            $table->unsignedBigInteger('company_id')->nullable()->after('user_type');
            $table->string('phone', 20)->nullable()->after('company_id');
            $table->string('address', 255)->nullable()->after('phone');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending')->after('address');
            
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['user_type', 'company_id', 'phone', 'address', 'status']);
        });
    }
};