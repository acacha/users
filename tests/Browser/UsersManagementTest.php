<?php

namespace Tests\Browser;

use Acacha\Users\Models\UserInvitation;
use App\User;
use Faker\Factory;
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
                ->assertSeeIn('div#user-list-box div.box-header h3.box-title', 'Users List')
                //See search form
                ->assertVisible('div.filter-bar')
                //See pagination
                ->assertVisible('div.vuetable-pagination')
                //See pagination info
                ->assertVisible('div.vuetable-pagination div.vuetable-pagination-info')
                ->assertSeeIn('div#user-list-box div.box-body div.vuetable-pagination div.vuetable-pagination-info', 'Displaying 1 to 15 of 76 users')
                //See paginator
                ->assertVisible('div.vuetable-pagination div.pagination');
            //Check number of columns/fields
            $this->assertEquals(8, count($browser->elements('div#user-list-box div.box-body table.vuetable thead tr th')));
            //Check number of rows/users
            $this->assertEquals(15, count($browser->elements('tr.um-user-row')));
        });

        $this->logout();
    }

    /**
     * Fill create user form.
     *
     * @param $browser
     * @param $user
     * @param null $name
     * @param null $email
     * @param null $password
     * @return User
     */
    private function fill_create_user_form($browser, $user, $name = null, $email = null, $password = null)
    {
        $this->login($browser,$user);
        $faker = Factory::create();
        $browser->visit('/management/users')
            ->assertMissing('#create-user-result')
            ->type("form#create-user-form input[name='name']", $name = ($name === null) ? $faker->name : $name)
            ->type("form#create-user-form input[name='email']", $email = ($email === null) ? $faker->email : $email)
            ->type("form#create-user-form input[name='password']",
                $password = ($password === null) ? $faker->password : $password)
            ->press('Create');

        return new User(['name' => $name, 'email' => $email, 'password' => $password]);
    }

    /**
     * Create user.
     * @group failing1
     * @test
     */
    public function create_user()
    {
        dump('create_user');
        $user = $this->createUserManagerUser();
        $this->browse(function ($browser) use ($user) {
            $newUser = $this->fill_create_user_form($browser, $user);
            //TODO not working
            //Assert see adding/inviting spinner/icon
            // $browser->waitFor('i#create-user-spinner');
            //Wait for ajax request to finish
//            $browser->waitUntilMissing('i#create-user-spinner');

            $browser->assertVisible('div#create-user-result');
            $browser->assertSeeIn('#create-user-result', 'User created!');
            $this->assertEquals($browser->value('#inputCreateUserName'),'');
            $this->assertEquals($browser->value('#inputCreateUserEmail'),'');
            $this->assertEquals($browser->value('#inputCreateUserPassword'),'');

            $this->assertDatabaseHas('users', [
                'name' => $newUser->name,
                'email' => $newUser->email
            ]);
        });
    }

    /**
     * Assert see validation error.
     *
     * @param $browser
     * @param $selector
     * @param $value
     */
    private function assertSeeValidationError($browser, $selector, $value)
    {
        $browser->waitFor($selector);
        $browser->assertSeeIn($selector, $value);
    }

    /**
     * Create user validation.
     *
     * @test
     */
    public function create_user_validation()
    {
        dump('create_user_validation');


        $user = $this->createUserManagerUser();
        $faker = Factory::create();
        $this->browse(function ($browser) use ($user,$faker) {
            $this->fill_create_user_form($browser, $user, '',$faker->email,$faker->password);
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserName',
                'The name field is required.');

            $this->fill_create_user_form($browser, $user, str_random(256),$faker->email,$faker->password);
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserName',
                'The name may not be greater than 255 characters.');

            $this->fill_create_user_form($browser, $user, $faker->name,'',$faker->password);
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserEmail',
                'The email field is required.');

            $this->fill_create_user_form($browser, $user, $faker->name,'dsasaddsa@dsasda',$faker->password);
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserEmail',
                'The email must be a valid email address.');

            $this->fill_create_user_form($browser, $user, $faker->name, $faker->email,'');
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserPassword',
                'The password field is required.');

            $this->fill_create_user_form($browser, $user, $faker->name, $faker->email,str_random(5));
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserPassword',
                'The password must be at least 6 characters.');
        });
    }

    // ******************************
    // * User invitations           *
    // ******************************

    /**
     * Check user invitations are shown correctly.
     *
     * @test
     * @return void
     */
    public function check_user_invitations_are_shown_correctly()
    {
        dump('check_user_invitations_are_shown_correctly');
        $user = $this->createUserManagerUser();
        $this->createUserInvitations(75);
        $this->browse(function (Browser $browser) use ($user) {
            $this->login($browser,$user)
                ->visit('/management/users')
                ->assertSeeIn('div#user-invitations-list-box div.box-header h3.box-title', 'Invitations List')
                //See search form
                ->assertVisible('div.filter-bar')
                //See pagination
                ->assertVisible('div.vuetable-pagination')
                //See pagination info
                ->assertVisible('div.vuetable-pagination div.vuetable-pagination-info')
                ->assertSeeIn('div#user-invitations-list-box div.box-body div.vuetable-pagination div.vuetable-pagination-info', 'Displaying 1 to 15 of 75 invitations')
                //See paginator
                ->assertVisible('div.vuetable-pagination div.pagination');
            //Check number of columns/fields
            $this->assertEquals(9, count($browser->elements('div#user-invitations-list-box div.box-body table.vuetable thead tr th')));
            //Check number of rows/users
            $this->assertEquals(15, count($browser->elements('tr.um-user-invitation-row')));
        });

        $this->logout();
    }

    /**
     * Add user invitation.
     *
     * @test
     */
    public function add_user_invitation()
    {
        dump('add_user_invitation');

        $user = $this->createUserManagerUser();
        $faker = Factory::create();
        $email = $faker->unique()->safeEmail;
        $this->browse(function (Browser $browser) use ($user,$email) {

            $this->login($browser,$user)
                ->visit('/management/users')
                ->assertMissing('i#add-user-invitation-spinner');

            $browser->type('#inputUserInvitationEmail',$email)
                    ->press('Invite');

            //Assert see adding/inviting spinner/icon
            $browser->assertVisible('i#add-user-invitation-spinner');
            //Wait for ajax request to finish
            $browser->waitUntilMissing('i#add-user-invitation-spinner');
            //Assert see result ok
            $browser->assertVisible('div#add-user-invitation-result');
            $browser->assertSeeIn('div#add-user-invitation-result', 'User invited!');
            //Assert email field has been cleared
            $this->assertEquals($browser->value('#inputUserInvitationEmail'),'');
        });

        $this->logout();
    }

    /**
     * Check validations using adding user invitations
     */
    public function check_validations_using_add_user_invitation()
    {
        //        'email' => 'required|email|max:255|unique:users',
//        TODO
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
        initialize_users_management_permissions();
        $user->assignRole('manage-users');
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
        return $this->createModels(User::class,$number);
    }

    /**
     * Create user invitations.
     *
     * @param null $number
     * @return mixed
     */
    private function createUserInvitations($number = null)
    {
        return $this->createModels(UserInvitation::class,$number);
    }

    /**
     * Create models.
     *
     * @param $model
     * @param null $number
     * @return mixed
     */
    private function createModels($model, $number = null) {
        $model = factory($model , $number)->create();
        return $model;
    }

}
