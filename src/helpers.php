<?php

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

if (! function_exists('initialize_users_management_permissions')) {
    function initialize_users_management_permissions()
    {
        $manageUsers = Role::fistOrCreate(['name' => 'manage-users']);
        Permission::fistOrCreate(['name' => 'list-users']);
//        Permission::create(['name' => 'create-user']);
        Permission::fistOrCreate(['name' => 'send-user-invitations']);

        $manageUsers->givePermissionTo('list-users');
        $manageUsers->givePermissionTo('send-user-invitations');

        app(PermissionRegistrar::class)->registerPermissions();

    }
}