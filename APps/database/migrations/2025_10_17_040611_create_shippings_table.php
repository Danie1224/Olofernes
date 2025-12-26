<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {Schema::create('shippings', function (Blueprint $table) {
        $table->id('shipping_id');
        $table->unsignedBigInteger('order_id');
        $table->string('address');
        $table->string('city');
        $table->string('state');
        $table->string('zip_code');
        $table->string('country');
        $table->string('tracking_number')->nullable();
        $table->enum('shipping_status', ['pending', 'shipped', 'delivered', 'cancelled'])->default('pending');
        $table->timestamps();
    
        $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};