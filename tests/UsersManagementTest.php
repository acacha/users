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
     * Sign in into application as user manager.
     *
     * @param null $driver
     * @return $this
     */
    protected function signInAsUserManager($driver = null)
    {
        return $this->signInWithRole('manage-users', $driver);
    }

    /**
     * Sign in with role.
     *
     * @param $role
     * @param null $driver
     */
    protected function signInWithRole($role, $driver = null)
    {
        $user = $this->create('App\User');
        initialize_users_management_permissions();
        $user->assignRole($role);
        $this->signIn($user,$driver);
        return $this;
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