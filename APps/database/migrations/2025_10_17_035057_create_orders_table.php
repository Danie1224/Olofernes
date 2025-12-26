<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {Schema::create('orders', function (Blueprint $table) {
        $table->id('order_id');

        // Foreign keys
        $table->string('customer_id');
        $table->unsignedBigInteger('product_id');
        $table->unsignedBigInteger('admin_id'); // Required admin assignment

        // Order details
        $table->integer('quantity')->default(1);
        $table->decimal('total_price', 10, 2);
        $table->enum('payment_method', ['cash', 'card', 'gcash', 'paypal'])->default('cash');
        $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
        $table->timestamp('order_date')->useCurrent();

        $table->timestamps();

        // Foreign key constraints
        $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
        $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        $table->foreign('admin_id')->references('admin_id')->on('admins')->restrictOnDelete();
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};