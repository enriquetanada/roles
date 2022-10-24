<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'permission_view',
            'permission_create',
            'permission_edit',
            'permission_delete',
            'role_view',
            'role_create',
            'role_edit',
            'role_delete',
            'user_view',
            'user_create',
            'user_edit',
            'user_delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        };

        //for super admin
        $role = Role::create(['name' => 'super-admin', 'guard_name' => 'api']);
        $role->givePermissionTo(Permission::all());

        //for users
        $role = Role::create(['name' => 'User', 'guard_name' => 'api']);
        $role->givePermissionTo(['permission_view', 'role_view', 'user_view']);

    }
}
