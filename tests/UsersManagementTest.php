<?php

namespace Tests;

use Spatie\Permission\Models\Permission;
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
     * @return $this
     */
    protected function signIn($user = null)
    {
        $user = $user ?: $this->create('App\User');

        $this->actingAs($user);

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
    protected function signInAsUserManager()
    {
        return $this->signInWithPermission('manage-users');
    }

    /**
     * Sign in into application as user with a specific permission.
     *
     * @param $permission
     * @return $this
     */
    private function signInWithPermission($permission)
    {
        $user = $this->create('App\User');
        $this->createPermissionIfNotExists($permission);
        $user->givePermissionTo($permission);
        $this->signIn($user);

        return $this;
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