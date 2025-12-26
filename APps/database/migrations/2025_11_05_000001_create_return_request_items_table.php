<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_request_items', function (Blueprint $table) {
            $table->id('return_request_item_id');
            $table->unsignedBigInteger('request_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamps();

            // Foreign keys
            $table->foreign('request_id')->references('request_id')->on('return_requests')->cascadeOnDelete();
            $table->foreign('order_item_id')->references('order_item_id')->on('order_items')->cascadeOnDelete();
            $table->foreign('product_id')->references('product_id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_request_items');
    }
};
