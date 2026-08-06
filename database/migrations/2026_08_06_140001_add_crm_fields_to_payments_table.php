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
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable()->default('bank_transfer')->after('amount');
            }
            if (! Schema::hasColumn('payments', 'cheque_number')) {
                $table->string('cheque_number')->nullable()->after('reference_number');
            }
            if (! Schema::hasColumn('payments', 'card_last_four')) {
                $table->string('card_last_four')->nullable()->after('cheque_number');
            }
            if (! Schema::hasColumn('payments', 'collected_by')) {
                $table->foreignId('collected_by')->nullable()->after('status')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['collected_by']);
            $table->dropColumn(['payment_method', 'cheque_number', 'card_last_four', 'collected_by']);
        });
    }
};
