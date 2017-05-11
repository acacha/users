<?php

namespace Tests;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Class UsersManagementTest.
 *
 * @package Tests
 */
trait UsersManagementTest
{
    /**
     * Sign in into application.
     *
     * @param null $user
     * @param null $driver
     * @return $this
     */
    protected function signIn($user = null, $driver = null)
    {
        $user = $user ?: $this->create('App\User');

        $this->actingAs($user, $driver);

        view()->share('signedIn',true);
        view()->share('user', $user);

        return $this;
    }

    /**
     * Sign in into application as admin user.
     *
     * @return $this
     */
    protected function signInAsAdmin()
    {
        return $this->signInWithPermission('manage-all');
    }

    /**
     * Sign in into application as user manager.
     *
     * @return $this
     */
    protected function signInAsUserManager($driver = null)
    {
        return $this->signInWithPermission('manage-users', $driver);
    }

    /**
     * Sign in into application as user with a specific role.
     *
     * @param $role
     * @param null $driver
     * @return $this
     */
    private function signInWithRole($role, $driver = null)
    {
        $user = $this->create('App\User');
        $this->createRoleIfNotExists($role);
        $user->assignRole($role);
        $this->signIn($user, $driver);

        return $this;
    }


    /**
     * Sign in into application as user with a specific permission.
     *
     * @param $permission
     * @return $this
     */
    private function signInWithPermission($permission, $driver = null)
    {
        $user = $this->create('App\User');
        $this->createPermissionIfNotExists($permission);
        $user->givePermissionTo($permission);
        $this->signIn($user, $driver);

        return $this;
    }

    /**
     * Create role if not exists.
     *
     * @param $role
     */
    private function createRoleIfNotExists($role) {
        Role::firstOrCreate(['name' => $role]);
        //Re-register role Laravel gates. See https://github.com/spatie/laravel-permission/issues/288
        app(PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * Create permission if not exists.
     *
     * @param $permission
     */
    private function createPermissionIfNotExists($permission) {
        Permission::firstOrCreate(['name' => $permission]);
        //Re-register permission Laravel gates. See https://github.com/spatie/laravel-permission/issues/288
        app(PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * Create a model using laravel factories.
     *
     * @param $class
     * @param array $attributes
     * @param null $times
     * @return mixed
     */
    protected function create($class, $attributes = [], $times = null)
    {
        return factory($class, $times)->create($attributes);
    }
}