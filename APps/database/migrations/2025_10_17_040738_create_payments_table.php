<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id('payment_id');
        $table->unsignedBigInteger('order_id');
        $table->string('customer_id');
        $table->enum('payment_method', ['credit_card', 'paypal', 'gcash', 'cod']);
        $table->decimal('amount', 10, 2);
        $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
        $table->timestamp('transaction_date')->useCurrent();
        $table->timestamps();
    
        $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete();
        $table->foreign('customer_id')->references('customer_id')->on('customers')->cascadeOnDelete();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
