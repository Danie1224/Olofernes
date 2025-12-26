<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            // Add admin processing fields if they don't exist
            if (!Schema::hasColumn('return_requests', 'processed_by')) {
                $table->unsignedBigInteger('processed_by')->nullable()->after('status');
                $table->foreign('processed_by')->references('admin_id')->on('admins')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('return_requests', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('processed_by');
            }

            if (!Schema::hasColumn('return_requests', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->nullable()->after('admin_notes');
            }

            if (!Schema::hasColumn('return_requests', 'return_date')) {
                $table->date('return_date')->nullable()->after('refund_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['processed_by']);
            $table->dropColumn([
                'processed_by',
                'admin_notes',
                'refund_amount',
                'return_date'
            ]);
        });
    }
};
