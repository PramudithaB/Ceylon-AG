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
        Schema::table('stock_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('stock_requests', 'priority')) {
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->after('requested_quantity');
            }
            if (! Schema::hasColumn('stock_requests', 'expected_delivery_date')) {
                $table->date('expected_delivery_date')->nullable()->after('priority');
            }
            if (! Schema::hasColumn('stock_requests', 'reason')) {
                $table->text('reason')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('stock_requests', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->dropColumn(['priority', 'expected_delivery_date', 'reason', 'attachment_path']);
        });
    }
};
