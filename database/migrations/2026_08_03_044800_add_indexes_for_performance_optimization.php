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
        try {
            Schema::table('products', function (Blueprint $table) {
                $table->index(['status', 'category_id'], 'idx_products_status_category');
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('product_assignments', function (Blueprint $table) {
                $table->index(['client_id', 'product_id'], 'idx_assignments_client_product');
                $table->index('assigned_at', 'idx_assignments_date');
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('client_sales', function (Blueprint $table) {
                $table->index(['client_id', 'selling_date'], 'idx_sales_client_date');
                $table->index(['product_id', 'selling_date'], 'idx_sales_product_date');
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('payments', function (Blueprint $table) {
                $table->index(['client_id', 'status'], 'idx_payments_client_status');
                $table->index('payment_date', 'idx_payments_date');
            });
        } catch (\Throwable $e) {}

        try {
            Schema::table('stock_requests', function (Blueprint $table) {
                $table->index(['client_id', 'status'], 'idx_stock_req_client_status');
            });
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('idx_products_status_category');
            });
            Schema::table('product_assignments', function (Blueprint $table) {
                $table->dropIndex('idx_assignments_client_product');
                $table->dropIndex('idx_assignments_date');
            });
            Schema::table('client_sales', function (Blueprint $table) {
                $table->dropIndex('idx_sales_client_date');
                $table->dropIndex('idx_sales_product_date');
            });
            Schema::table('payments', function (Blueprint $table) {
                $table->dropIndex('idx_payments_client_status');
                $table->dropIndex('idx_payments_date');
            });
            Schema::table('stock_requests', function (Blueprint $table) {
                $table->dropIndex('idx_stock_req_client_status');
            });
        } catch (\Throwable $e) {}
    }
};
