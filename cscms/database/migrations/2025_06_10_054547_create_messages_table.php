<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->unsignedBigInteger('sender_company_id');
            $table->unsignedBigInteger('receiver_company_id');
            $table->unsignedBigInteger('sender_user_id');
            $table->unsignedBigInteger('receiver_user_id')->nullable();
            $table->string('subject', 200)->nullable();
            $table->text('message_body');
            $table->enum('message_type', ['general', 'order_inquiry', 'quality_feedback', 'delivery_update', 'system_notification'])->default('general');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('sender_company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->foreign('receiver_company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->foreign('sender_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};