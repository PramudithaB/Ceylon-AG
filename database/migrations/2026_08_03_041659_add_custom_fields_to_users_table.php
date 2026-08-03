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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('business_name')->nullable()->after('last_name');
            $table->string('nic')->nullable()->unique()->after('business_name');
            $table->string('phone')->nullable()->after('nic');
            $table->text('address')->nullable()->after('phone');
            $table->string('district')->nullable()->after('address');
            $table->string('province')->nullable()->after('district');
            $table->string('status')->default('pending')->after('email_verified_at'); // pending, approved, rejected
            $table->string('profile_photo_path', 2048)->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'business_name',
                'nic',
                'phone',
                'address',
                'district',
                'province',
                'status',
                'profile_photo_path',
            ]);
        });
    }
};
