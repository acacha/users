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
        dump(__FUNCTION__ );
        $manager = $this->createUserManagerUser();

        $this->browse(function (Browser $browser) use ($manager) {
            $this->login($browser,$manager)
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
        dump(__FUNCTION__ );

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
        dump(__FUNCTION__ );
        $manager = $this->createUserManagerUser();
        $this->createUsers(75);
        $this->browse(function (Browser $browser) use ($manager) {
            $this->login($browser,$manager)
                ->visit('/management/users?expand')
                ->assertSeeIn('div#users-list-box div.box-header h3.box-title', 'Users List')
                //See search form
                ->assertVisible('div.filter-bar')
                //See pagination
                ->assertVisible('div.vuetable-pagination')
                //See pagination info
                ->assertVisible('div.vuetable-pagination div.vuetable-pagination-info')
                ->assertSeeIn('div#users-list-box div.box-body div.vuetable-pagination div.vuetable-pagination-info', 'Displaying 1 to 15 of 76 users')
                //See paginator
                ->assertVisible('div.vuetable-pagination div.pagination');
            //Check number of columns/fields
            $this->assertEquals(8, count($browser->elements('div#users-list-box div.box-body table.vuetable thead tr th')));
            //Check number of rows/users
            $this->assertEquals(15, count($browser->elements('tr.um-user-row')));
        });

        $this->logout();
    }

    // ******************************
    // * Users Tests                *
    // ******************************

    /**
     * Create user.
     *
     * @test
     */
    public function create_user()
    {
        dump(__FUNCTION__ );
        $manager = $this->createUserManagerUser();
        $this->browse(function ($browser) use ($manager) {
            $newUser = $this->fill_create_user_form($browser, $manager);

            $browser->waitFor('div#create-user-result');
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
     * Create user validation.
     *
     * @test
     */
    public function create_user_validation()
    {
        dump(__FUNCTION__ );

        $manager = $this->createUserManagerUser();
        $faker = Factory::create();
        $this->browse(function ($browser) use ($manager,$faker) {
            $this->fill_create_user_form($browser, $manager, '',$faker->email,$faker->password);
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserName',
                'The name field is required.');

            $this->fill_create_user_form($browser, $manager, str_random(256),$faker->email,$faker->password);
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserName',
                'The name may not be greater than 255 characters.');

            $this->fill_create_user_form($browser, $manager, $faker->name,'',$faker->password);
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserEmail',
                'The email field is required.');

            $this->fill_create_user_form($browser, $manager, $faker->name,'dsasaddsa@dsasda',$faker->password);
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserEmail',
                'The email must be a valid email address.');

            $this->fill_create_user_form($browser, $manager, $faker->name, $faker->email,'');
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserPassword',
                'The password field is required.');

            $this->fill_create_user_form($browser, $manager, $faker->name, $faker->email,str_random(5));
            $this->assertSeeValidationError($browser,'span#errorForInputCreateUserPassword',
                'The password must be at least 6 characters.');
        });
    }

    /**
     * Show user.
     *
     * @test
     */
    public function show_user()
    {
        dump(__FUNCTION__ );
        $user = $this->createUsers();
        $this->activeUserDetailRowAndExecuteAction($user,'show');
    }

    /**
     * Modify user.
     * @group caca
     * @test
     */
    public function modify_user()
    {
        dump(__FUNCTION__ );
        $user = $this->createUsers();
        $this->activeUserDetailRowAndExecuteAction($user,'edit');
    }

    /**
     * Delete user.
     *
     * @test
     */
    public function delete_user()
    {
        dump(__FUNCTION__ );
        $user = $this->execute_delete_user();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    /**
     * Delete user cancel.
     *
     * @test
     */
    public function delete_user_cancel()
    {
        dump(__FUNCTION__ );
        $user = $this->execute_delete_user(false);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    /**
     * Unauthorized users see users list action buttons disabled.
     *
     * @test
     */
    public function unauthorized_users_see_users_list_action_buttons_disabled()
    {
        dump(__FUNCTION__ );

        $user = $this->createUserWithOnlySeePermissions();

        $this->browse(function ($browser) use ($user) {
            $this->login($browser, $user);
            $browser->visit('/management/users?expand')
                ->assertVisible('[id^=delete-user-]:disabled')
                ->assertVisible('[id^=edit-user-]:disabled')
                ->assertVisible('[id^=show-user-]:disabled');
        });
    }

    /**
     * ----------------------------------------
     * Private/helper test functions for users.
     * ----------------------------------------
     */

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
     * Fill create user form.
     *
     * @param $browser
     * @param $manager
     * @param null $name
     * @param null $email
     * @param null $password
     * @return User
     */
    private function fill_create_user_form($browser, $manager, $name = null, $email = null, $password = null)
    {
        $this->login($browser,$manager);
        $faker = Factory::create();
        $browser->visit('/management/users?expand')
            ->assertMissing('#create-user-result')
            ->type("form#create-user-form input[name='name']", $name = ($name === null) ? $faker->name : $name)
            ->type("form#create-user-form input[name='email']", $email = ($email === null) ? $faker->email : $email)
            ->type("form#create-user-form input[name='password']",
                $password = ($password === null) ? $faker->password : $password)
            ->press('Create');

        return new User(['name' => $name, 'email' => $email, 'password' => $password]);
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
     * Active user detail row.
     *
     * @param $user
     * @param $action
     */
    private function activeUserDetailRowAndExecuteAction($user,$action)
    {
        $manager = $this->createUserManagerUser();
        $this->browse(function ($browser) use ($manager,$user, $action) {

            $this->login($browser,$manager);
            $browser->visit('/management/users')
                ->assertMissing('#user-' . $user->id . '-detail-row')
                ->press('#' . $action . '-user-' . $user->id)
                ->assertVisible('#user-' . $user->id . '-detail-row')
                ->assertVisible('#editable-field-user-' . $user->id . '-name' )
                ->assertVisible('#editable-field-user-' . $user->id . '-email');
            if ($action == 'show') {
                $browser->assertVisible('#editable-field-user-' . $user->id . '-name' . ' label i.fa-edit')
                    ->assertSeeIn('#editable-field-user-' . $user->id . '-name' . ' label', $user->name)
                    ->assertMissing('#editable-field-user-' . $user->id . '-name' . ' div.input-group')
                    ->assertVisible('#editable-field-user-' . $user->id . '-email'. ' label i.fa-edit')
                    ->assertMissing('#editable-field-user-' . $user->id . '-email' . ' div.input-group')
                    ->assertSeeIn('#editable-field-user-' . $user->id . '-email' . ' label', $user->email);
            } elseif ($action == 'edit') {
                $browser->assertVisible('#editable-field-user-' . $user->id . '-name' . ' div.input-group')
                    ->assertMissing('#editable-field-user-' . $user->id . '-name' . ' label i.fa-edit')
                    ->assertVisible('#editable-field-user-' . $user->id . '-email'. ' div.input-group')
                    ->assertMissing('#editable-field-user-' . $user->id . '-email' . ' label i.fa-edit');

                $faker = Factory::create();

                $this->executeActionEdit("user", $browser,$user->id ,'name', $name = $faker->name);
                $this->executeActionEdit("user", $browser,$user->id ,'email', $email = $faker->email);

                $this->assertDatabaseHas('users', [
                    'id' => $user->id,
                    'name' => $name,
                    'email' => $email
                ]);
            }

        });
    }

    /**
     * Execute action edit.
     *
     * @param $browser
     * @param $id resource id
     * @param $field
     */
    private function executeActionEdit( $resource , $browser, $id, $field, $newValue)
    {
        $browser->type('#input-edit-' . $resource . '-' . $id . '-field-' . $field, $newValue)
            ->press('#edit-button-' . $resource . '-' . $id . '-field-' . $field);

        //Editable as been changed to read mode
        $browser->assertVisible('#editable-field-' . $resource . '-' . $id . '-' . $field . ' label i.fa-edit')
            ->assertSeeIn('#editable-field-' . $resource . '-' . $id . '-'. $field . ' label', $newValue)
            ->assertMissing('#editable-field-' . $resource . '-' . $id . '-' . $field . ' div.input-group');
            // See new value in users list
            $browser->assertSeeIn('div#' . $resource . 's-list-box ', $newValue);

    }

    /**
     * Execute delete user.
     *
     * @param bool $confirm
     * @return mixed
     */
    private function execute_delete_user($confirm = true) {
        $manager = $this->createUserManagerUser();
        $user = $this->createUsers();
        $this->browse(function ($browser) use ($manager, $user, $confirm) {
            $this->login($browser,$manager);
            $browser->visit('/management/users')
                ->press('#delete-user-' . $user->id)
                ->waitFor('div#confirm-user-deletion-modal')
                ->assertSeeIn('div#confirm-user-deletion-modal','Are you sure you want to delete user?');

                if ($confirm) {
                    $browser->press('#confirm-user-deletion-button');
                    $browser->waitUntilMissing('#delete-user-' . $user->id);
                }
        });

        return $user;
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
        dump(__FUNCTION__ );
        $manager = $this->createUserManagerUser();
        $this->createUserInvitations(75);
        $this->browse(function (Browser $browser) use ($manager) {
            $this->login($browser,$manager)
                ->visit('/management/users?expand')
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
        dump(__FUNCTION__ );

        $manager = $this->createUserManagerUser();
        $faker = Factory::create();
        $email = $faker->unique()->safeEmail;
        $this->browse(function (Browser $browser) use ($manager,$email) {

            $this->login($browser,$manager)
                ->visit('/management/users?expand')
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
     *
     * @test
     */
    public function check_validations_using_add_user_invitation()
    {
        dump(__FUNCTION__ );
        //        'email' => 'required|email|max:255|unique:users',
//        TODO
    }

    /**
     * Show user invitation.
     *
     * @test
     */
    public function show_user_invitation()
    {
        dump(__FUNCTION__ );
        $invitation = $this->createUserInvitations();
        $this->activeUserInvitationDetailRowAndExecuteAction($invitation,'show');
    }

    /**
     * Modify user invitation.
     *
     * @test
     */
    public function modify_user_invitation()
    {
        dump(__FUNCTION__ );
        $invitation = $this->createUserInvitations();
        $this->activeUserInvitationDetailRowAndExecuteAction($invitation,'edit');
    }

    /**
     * Delete user invitation.
     *
     * @test
     */
    public function delete_user_invitation()
    {
        dump(__FUNCTION__ );

        $user = $this->execute_delete_user_invitation();

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    /**
     * Delete user invitation cancel.
     *
     * @test
     */
    public function delete_user_invitation_cancel()
    {
        dump(__FUNCTION__ );

        $user = $this->execute_delete_user_invitation(false);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }

    /**
     * Unauthorized users see users invitations list action buttons disabled.
     *
     * @test
     */
    public function unauthorized_users_see_user_invitations_list_action_buttons_disabled()
    {
        dump(__FUNCTION__ );
        $this->createUserInvitations(3);
        $user = $this->createUserWithOnlySeePermissions();
        $this->browse(function ($browser) use ($user) {
            $this->login($browser, $user);
            $browser->visit('/management/users?expand')
                ->driver->executeScript('document.getElementById("users-invitations-list").scrollIntoView();');
            $browser->assertVisible('[id^=delete-user-invitation]:disabled')
                ->assertVisible('[id^=edit-user-invitation]:disabled')
                ->assertVisible('[id^=show-user-invitation]:disabled');
        });
    }

    /**
     * ----------------------------------------------------
     * Private/helper test functions for user invitations.
     * ----------------------------------------------------
     */

    /**
     * Create user with only see permissions.
     *
     * @return mixed
     */
    private function createUserWithOnlySeePermissions()
    {
        initialize_users_management_permissions();
        $user = $this->createUsers();
        $user->givePermissionTo('see-manage-users-view');
        $user->givePermissionTo('list-users');
        return $user;
    }

    /**
     * Execute delete user invitation.
     *
     * @param bool $confirm
     * @return mixed
     */
    private function execute_delete_user_invitation($confirm = true) {
        $manager = $this->createUserManagerUser();
        $user = $this->createUsers();
        $this->browse(function ($browser) use ($manager, $user, $confirm) {
            $this->login($browser,$manager);
            $browser->visit('/management/users')
                ->press('#delete-user-' . $user->id)
                ->waitFor('div#confirm-user-deletion-modal')
                ->assertSeeIn('div#confirm-user-deletion-modal','Are you sure you want to delete user?');

            if ($confirm) {
                $browser->press('#confirm-user-deletion-button');
                $browser->waitUntilMissing('#delete-user-' . $user->id);
            }
        });

        return $user;
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
     * Active user invitation detail row.
     *
     * @param $invitation
     * @param $action
     */
    private function activeUserInvitationDetailRowAndExecuteAction($invitation,$action)
    {
        $manager = $this->createUserManagerUser();
        $this->browse(function ($browser) use ($manager,$invitation, $action) {

            $this->login($browser,$manager);
            $browser->visit('/management/users?expand')
                ->driver->executeScript('document.getElementById("users-invitations-list").scrollIntoView();');
            $browser->assertMissing('#user-invitation-' . $invitation->id . '-detail-row')
                ->press('#' . $action . '-user-invitation-' . $invitation->id)
                ->assertVisible('#user-invitation-' . $invitation->id . '-detail-row')
                ->assertVisible('#editable-field-user-invitation-' . $invitation->id . '-email')
                ->assertVisible('#editable-field-user-invitation-' . $invitation->id . '-state');

            if ($action == 'show') {
                $browser->assertVisible('#editable-field-user-invitation-' . $invitation->id . '-email' . ' label i.fa-edit')
                    ->assertSeeIn('#editable-field-user-invitation-' . $invitation->id . '-email' . ' label', $invitation->email)
                    ->assertMissing('#editable-field-user-invitation-' . $invitation->id . '-email' . ' div.input-group')
                    ->assertVisible('#editable-field-user-invitation-' . $invitation->id . '-state'. ' label i.fa-edit')
                    ->assertMissing('#editable-field-user-invitation-' . $invitation->id . '-state' . ' div.input-group')
                    ->assertSeeIn('#editable-field-user-invitation-' . $invitation->id . '-state' . ' label', $invitation->state);;
            } elseif ($action == 'edit') {
                $browser->assertVisible('#editable-field-user-invitation-' . $invitation->id . '-email' . ' div.input-group')
                    ->assertMissing('#editable-field-user-invitation-' . $invitation->id . '-email' . ' label i.fa-edit')
                    ->assertVisible('#editable-field-user-invitation-' . $invitation->id . '-state'. ' div.input-group')
                    ->assertMissing('#editable-field-user-invitation-' . $invitation->id . '-state' . ' label i.fa-edit');

                $faker = Factory::create();

                $this->executeActionEdit("user-invitation", $browser,$invitation->id ,'email', $email = $faker->email);
                $this->executeActionEdit("user-invitation", $browser,$invitation->id ,'state', 'accepted');

                $this->assertDatabaseHas('user_invitations', [
                    'id' => $invitation->id,
                    'email' => $email,
                    'state' => 'accepted'
                ]);
            }

        });
    }

    /**
     * ----------------------------------------------------
     * Global Helpers.
     * ----------------------------------------------------
     */

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
