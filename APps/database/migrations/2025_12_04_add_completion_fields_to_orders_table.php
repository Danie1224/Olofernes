<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add completion tracking columns
            $table->timestamp('completed_at')->nullable()->after('updated_at');
            $table->unsignedBigInteger('completed_by_admin_id')->nullable()->after('completed_at');
            
            // Add foreign key for the admin who completed the order
            $table->foreign('completed_by_admin_id')
                ->references('admin_id')
                ->on('admins')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['completed_by_admin_id']);
            $table->dropColumn(['completed_at', 'completed_by_admin_id']);
        });
    }
};
