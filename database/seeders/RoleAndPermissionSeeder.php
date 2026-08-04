<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'manage-users',
            'approve-clients',
            'view-admin-dashboard',
            'edit-profile',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Create Roles
        $superAdminRole = Role::findOrCreate('Super Admin', 'web');
        $adminRole = Role::findOrCreate('Admin', 'web');
        $clientRole = Role::findOrCreate('Client', 'web');
        $refRole = Role::findOrCreate('Ref', 'web');

        // Give permissions to roles
        $superAdminRole->givePermissionTo(Permission::all());
        $adminRole->givePermissionTo(['view-admin-dashboard', 'approve-clients', 'edit-profile']);
        $clientRole->givePermissionTo(['edit-profile']);
        $refRole->givePermissionTo(['edit-profile']);

        // Create Default Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@ceylonag.com'],
            [
                'name' => 'Super Administrator',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'business_name' => 'Ceylon AG HQ',
                'nic' => '199000000000',
                'phone' => '+94770000000',
                'address' => 'Colombo 01',
                'district' => 'Colombo',
                'province' => 'Western',
                'password' => Hash::make('password'),
                'status' => User::STATUS_APPROVED,
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->update(['role' => User::ROLE_ADMIN]);
        $superAdmin->assignRole($superAdminRole);

        // Create Default Ref User (Sales Representative)
        $refUser = User::firstOrCreate(
            ['email' => 'ref@ceylonag.com'],
            [
                'name' => 'Sales Representative',
                'first_name' => 'Sales',
                'last_name' => 'Representative',
                'business_name' => 'Ceylon AG Sales',
                'nic' => '199200000000',
                'phone' => '+94771112233',
                'address' => 'Colombo 03',
                'district' => 'Colombo',
                'province' => 'Western',
                'password' => Hash::make('password'),
                'status' => User::STATUS_APPROVED,
                'role' => User::ROLE_REF,
                'email_verified_at' => now(),
            ]
        );
        $refUser->update(['role' => User::ROLE_REF]);
        $refUser->assignRole($refRole);
    }
}
