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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Ceylon Agro Marketing (Pvt) Ltd.');
            $table->text('address')->default("No. 123, Agribusiness Zone, Colombo, Sri Lanka");
            $table->string('phone')->default('+94 11 234 5678');
            $table->string('email')->default('info@ceylonag.com');
            $table->string('website')->default('www.ceylonag.com');
            $table->string('logo_path')->default('images/logo.png');

            // Bank Details
            $table->string('bank_name')->default('Bank of Ceylon');
            $table->string('branch')->default('Corporate Branch, Colombo');
            $table->string('account_name')->default('Ceylon Agro Marketing (Pvt) Ltd');
            $table->string('account_number')->default('000847362910');
            $table->string('swift_code')->nullable()->default('BCEYLKLX');

            // Default Notes / Terms
            $table->text('default_terms')->nullable();
            $table->string('default_delivery_period')->nullable()->default('3-5 business days upon order confirmation');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
