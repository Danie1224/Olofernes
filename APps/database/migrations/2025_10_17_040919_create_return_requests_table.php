<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedBigInteger('order_id');
            $table->string('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'refunded'])->default('pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamps();
        
            $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete();
            $table->foreign('customer_id')->references('customer_id')->on('customers')->cascadeOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
