<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_request_items', function (Blueprint $table) {
            $table->enum('reason', [
                'Damaged During Shipment',
                'Defective Product',
                'Wrong Item Received',
                'Not as Described',
                'Other'
            ])->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('return_request_items', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
};
