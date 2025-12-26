<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->id('user_voucher_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('voucher_id');
            $table->enum('status', ['available', 'used'])->default('available');
            $table->timestamps();

            $table->unique(['user_id','voucher_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('voucher_id')->references('voucher_id')->on('vouchers')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_vouchers');
    }
};


