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
        Schema::create('product_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('assignment_number')->unique();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('assigned_by')->constrained('users');
            $table->integer('quantity');
            $table->decimal('dealer_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->decimal('total_dealer_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamp('assigned_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_assignments');
    }
};
