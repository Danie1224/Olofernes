<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, expand the enum to include all values (old and new)
        Schema::table('user_vouchers', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'available', 'used'])->default('active')->change();
        });
        
        // Now update the data to use new values
        DB::statement("UPDATE user_vouchers SET status = 'available' WHERE status = 'active'");
        DB::statement("UPDATE user_vouchers SET status = 'used' WHERE status = 'inactive'");
        
        // Finally, change the enum to only have the new values
        Schema::table('user_vouchers', function (Blueprint $table) {
            $table->enum('status', ['available', 'used'])->default('available')->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_vouchers', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
        });
    }
};
