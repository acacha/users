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

        $this->browse(function (Browser $browser) use ($user) {
            $this->login($browser,$user)
                ->visit('/management/users')
                ->assertTitleContains('Users Management')
                ->assertSeeLink('Users');
        });

        $this->logout();
    }

    /**
     * Unauthorized users see users management menu entry.
     *
     * @test
     * @return void
     */
    public function unauthorized_users_dont_see_users_managment_menu_entry()
    {
        dump('unauthorized_users_dont_see_users_managment_menu_entry');

        $user = $this->createUsers();
        $this->browse(function (Browser $browser) use ($user){
            $this->login($browser,$user)
                ->visit('/home')
                ->assertDontSeeLink('Users');
        });

        $this->logout();
    }

    /**
     * Authorized users see users management menu entry.
     *
     * @group failing
     * @test
     * @return void
     */
    public function check_users_are_shown_correctly()
    {
        dump('check_users_are_shown_correctly');
        $user = $this->createUserManagerUser();
        $this->createUsers(75);
        $this->browse(function (Browser $browser) use ($user) {
            $this->login($browser,$user)
                ->visit('/management/users')
                ->assertSeeIn('div.box div.box-header h3.box-title', 'Users List')
                //See search form
                ->assertVisible('div.filter-bar')
                //See pagination
                ->assertVisible('div.vuetable-pagination')
                //See pagination info
                ->assertVisible('div.vuetable-pagination div.vuetable-pagination-info')
                ->assertSeeIn('div.vuetable-pagination div.vuetable-pagination-info', 'Displaying 1 to 15 of 76 users')
                //See paginator
                ->assertVisible('div.vuetable-pagination div.pagination');
            //Check number of columns/fields
            $this->assertEquals(7, count($browser->elements('table.vuetable thead tr th')));
            //Check number of rows/users
            $this->assertEquals(15, count($browser->elements('.um-user-row')));
        });

        $this->logout();
    }

    /**
     * Login.
     *
     * @param $browser
     * @param $user
     */
    private function login($browser,$user) {
        $browser->loginAs($user);
        view()->share('signedIn',true);
        view()->share('user', $user);
        return $browser;
    }

    /**
     * Logout.
     */
    private function logout()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/home')
                ->click('#user_menu')
                ->click('#logout')
                ->pause(2000);
        });
    }

    /**
     * Create a user with manage users permission.
     *
     * @return mixed
     */
    private function createUserManagerUser()
    {
        $user = $this->createUsers();
        Permission::firstOrCreate(['name' => 'manage-users']);
        $user->givePermissionTo('manage-users');
        return $user;
    }

    /**
     * Create users.
     *
     * @param null $number
     * @return mixed
     */
    private function createUsers($number = null)
    {
        $user = factory('App\User',$number)->create();
        return $user;
    }

}
