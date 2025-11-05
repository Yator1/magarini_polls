<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissionNames = [
            'add_users',
            'manage_users',
            'edit_member_details',
            'user_management_access',
            'permission_create',
            'permission_edit',
            'permission_show',
            'permission_delete',
            'permission_access',
            'role_create',
            'role_edit',
            'role_show',
            'role_delete',
            'role_access',
            'user_create',
            'user_edit',
            'user_show',
            'user_delete',
            'user_access',
        ];

        foreach ($permissionNames as $permissionName) {
            if (!Permission::where('name', $permissionName)->exists()) {
                Permission::create(['name' => $permissionName]);
            }
        }

        // Assign permissions to roles
        $superAdmin = Role::findByName('super_admin');
        $admin = Role::findByName('admin');
        $chief_officer = Role::findByName('chief_officer');
        
        $superAdmin->syncPermissions($permissionNames);
        $admin->syncPermissions(array_diff($permissionNames, [
            'role_create',
            'role_edit',
            'role_show',
            'role_delete',
            'role_access',
        ]));
        // $chief_officer->syncPermissions([
        //     'user_create',
        //     'user_edit',
        //     'user_show',
        //     'user_delete',
        //     'user_access',
        // ]);
    }
}
