<?php

use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

if (! function_exists('role_first_or_create')) {
    /**
     * Create  role by name or retrieve role if already exists.
     * @param $role
     * @return \Illuminate\Database\Eloquent\Model|\Spatie\Permission\Contracts\Role|Role
     */
    function role_first_or_create($role)
    {
        try {
            return Role::create(['name' => $role]);
        } catch (RoleAlreadyExists $e) {
            return Role::findByName($role);
        }
    }
}

if (! function_exists('permission_first_or_create')) {
    /**
     * Create permission by name or retrieve permission if already exists.
     *
     * @param $permission
     * @return \Illuminate\Database\Eloquent\Model|\Spatie\Permission\Contracts\Permission
     */
    function permission_first_or_create($permission)
    {
        try {
            return Permission::create(['name' => $permission]);
        } catch (PermissionAlreadyExists $e) {
            return Permission::findByName($permission);
        }
    }
}

if (! function_exists('give_permission_to_role')) {
    function give_permission_to_role($role,$permission)
    {
        try {
            $role->givePermissionTo($permission);
        } catch (Illuminate\Database\QueryException $e) {
            dump('Permissions ' . $permission . ' already assigned to role ' . $role->name);
        }
    }
}

if (! function_exists('initialize_users_management_permissions')) {
    function initialize_users_management_permissions()
    {
        $manageUsers = role_first_or_create('manage-users');

        //USERS
        permission_first_or_create('see-manage-users-view');
        permission_first_or_create('list-users');
        permission_first_or_create('create-users');
        permission_first_or_create('view-users');
        permission_first_or_create('edit-users');
        permission_first_or_create('delete-users');

        give_permission_to_role($manageUsers,'see-manage-users-view');
        give_permission_to_role($manageUsers,'list-users');
        give_permission_to_role($manageUsers,'create-users');
        give_permission_to_role($manageUsers,'view-users');
        give_permission_to_role($manageUsers,'edit-users');
        give_permission_to_role($manageUsers,'delete-users');

        //USER INVITATIONS
        permission_first_or_create('see-manage-user-invitations-view');
        permission_first_or_create('list-user-invitations');
        permission_first_or_create('send-user-invitations');
        //TODO diferences between send and create user invitations?
        //permission_first_or_create('create-user-invitations');
        permission_first_or_create('view-user-invitations');
        permission_first_or_create('edit-user-invitations');
        permission_first_or_create('delete-user-invitations');

        give_permission_to_role($manageUsers,'see-manage-user-invitations-view');
        give_permission_to_role($manageUsers,'list-user-invitations');
        give_permission_to_role($manageUsers,'send-user-invitations');
        give_permission_to_role($manageUsers,'view-user-invitations');
        give_permission_to_role($manageUsers,'edit-user-invitations');
        give_permission_to_role($manageUsers,'delete-user-invitations');

        app(PermissionRegistrar::class)->registerPermissions();

    }
}

