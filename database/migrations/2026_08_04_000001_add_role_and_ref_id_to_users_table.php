<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('client')->after('status');
            $table->foreignId('ref_id')->nullable()->after('role')->constrained('users')->onDelete('set null');
        });

        // Migrate existing users to appropriate roles based on spatie model_has_roles or email
        DB::table('users')->get()->each(function ($user) {
            $role = 'client';

            // Check if user is Super Admin or Admin via Spatie pivot table if it exists
            $hasAdminRole = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->whereIn('roles.name', ['Super Admin', 'Admin', 'admin'])
                ->exists();

            if ($hasAdminRole || strtolower($user->email) === 'admin@ceylonag.com') {
                $role = 'admin';
            }

            DB::table('users')->where('id', $user->id)->update(['role' => $role]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ref_id']);
            $table->dropColumn(['role', 'ref_id']);
        });
    }
};
