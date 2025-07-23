<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('payer');
            $table->string('payee');
            $table->decimal('amount', 12, 2);
            $table->string('method')->nullable();
            $table->enum('status', ['pending', 'completed', 'refunded'])->default('completed');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}; 