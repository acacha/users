<?php

namespace Tests\Browser;

use Spatie\Permission\Models\Permission;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class UsersManagementTest.
 *
 * @package Tests\Browser
 */
class UsersManagementTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Authorized users see users management menu entry.
     *
     * @test
     * @return void
     */
    public function authorized_users_see_users_managment_menu_entry()
    {
        dump('authorized_users_see_users_managment_menu_entry');
        $user = $this->createUserManagerUser();

        $user->givePermissionTo('manage-users');
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/management/users')
                ->assertTitleContains('Users Management')
                ->assertSeeLink('Users');
        });
    }

    /**
     * Create a user with manage users permission.
     *
     * @return mixed
     */
    private function createUserManagerUser()
    {
        $user = factory('App\User')->create();
        view()->share('signedIn',true);
        view()->share('user', $user);
        Permission::firstOrCreate(['name' => 'manage-users']);
        return $user;
    }

}
