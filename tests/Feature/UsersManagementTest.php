<?php

namespace Tests\Feature;

use Acacha\Users\Events\UserCreated;
use Acacha\Users\Events\UserInvited;
use Acacha\Users\Mail\UserInvitation;
use Acacha\Users\Models\UserInvitation as UserInvitationModel;
use App\User;
use Auth;
use Event;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mail;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Class UsersManagementTest.
 *
 * @package Tests\Feature
 */
class UsersManagementTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * Unauthorized user cannot browse users management.
     *
     * @test
     * @return void
     */
    public function unauthorized_user_cannot_browse_users_management()
    {
        $response = $this->get('/management/users');

        $response->assertStatus(302);
    }

    /**
     * A user cannot browse users management.
     *
     * @test
     * @return void
     */
    public function a_user_cannot_browse_users_management()
    {
        $this->signIn();
        $response = $this->get('/management/users');
        $response->assertStatus(403);
    }

    /**
     * Check app already have manage-users permission.
     *
     * @test
     */
    public function check_app_already_have_manage_users_permissions()
    {
        $this->seedPermissions();
        $this->assertRoleExists('manage-users');
        $this->assertPermissionExists('see-manage-users-view');
        $this->assertPermissionExists('list-users');
        $this->assertPermissionExists('send-user-invitations');
    }

    /**
     * Authorized user can browse users managment.
     *
     * @test
     * @return void
     */
    public function authorized_user_can_browse_users_management()
    {
        $response = $this->signInAsUserManager()
            ->get('/management/users');

        $response->assertStatus(200);
    }

    /**
     * Api show a 302 error listing all users if request is not done by XHR.
     *
     * @test
     */
    public function api_show_302_listing_all_users_if_no_xhr_request()
    {
        $this->get('/api/v1/management/users')
             ->assertStatus(302)
             ->assertRedirect('login');
    }

    /**
     * Api show 401 listing all users for unauthorized users.
     *
     * @test
     */
    public function api_show_401_listing_all_users_for_unauthorized_users()
    {
        $this->json('GET','/api/v1/management/users')
            ->assertStatus(401);
    }


    /**
     * Api show an user for authorized users correctly.
     *
     * @test
     */
    public function api_show__an_user_for_authorized_users_correctly()
    {
        $this->signInAsUserManager('api')
            ->json('GET', '/api/v1/management/users')
            ->assertStatus(200)

            ->assertExactJson([
                'current_page' => 1,
                'data' => [
                    ['id'=> Auth::user()->id,
                    'name' =>  Auth::user()->name,
                    'email' => Auth::user()->email,
                    'created_at' => Auth::user()->created_at->toDateTimeString(),
                    'updated_at' => Auth::user()->updated_at->toDateTimeString()
                    ]
                ],
                'from' => 1,
                'last_page' => 1,
                'next_page_url' => null,
                'per_page' => 15,
                'prev_page_url' => null,
                'to' => 1,
                'total' => 1
            ]);
    }

    /**
     * Api show all users for_authorized_users.
     *
     * @test
     */
    public function api_show__all_users_for_authorized_users_with_correct_structure()
    {
        factory('App\User',10)->create();
        $this->signInAsUserManager('api')
            ->json('GET','/api/v1/management/users')
            ->assertJsonStructure(['data' => [
                    '*' => [
                        'id', 'name', 'email','created_at','updated_at'
                ]
            ]]);
    }

    /**
     * Api show a 302 error creating a user if request is not done by XHR.
     *
     * @test
     */
    public function api_show_302_creating_user_if_no_xhr_request()
    {
        $this->post('/api/v1/management/users')
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    /**
     * Api show 401 listing creating_user for unauthorized users.
     * @test
     */
    public function api_show_401_creating_user_for_unauthorized_users()
    {
        $this->json('POST','/api/v1/management/users')
            ->assertStatus(401);
    }

    /**
     * Post user creation.
     */
    private function post_user_creation($name, $email, $password)
    {
        return $this->json('POST','/api/v1/management/users', [
            'name' => $name,
            'email' => $email ,
            'password' => bcrypt($password)
        ]);
    }

    /**
     * Api create user.
     *
     * @test
     */
    public function api_create_user_check_validations()
    {
        $faker = Factory::create();

        $this->publishFactories();
        $this->signInAsUserManager('api');

        $response = $this->post_user_creation('', $faker->unique()->safeEmail, 'secret');
        $this->assertResponseValidation($response,422,'name','The name field is required.');

        $response = $this->post_user_creation(str_random(256), $faker->unique()->safeEmail, 'secret');
        $this->assertResponseValidation($response,422,'name','The name may not be greater than 255 characters.');

        $response = $this->post_user_creation($faker->name, '', 'secret');
        $this->assertResponseValidation($response,422,'email','The email field is required.');

        $response = $this->post_user_creation($faker->name, 'sdsfsdfsfdsdfe@dadddaasdasdaseqwerqwqqweqwqwewqeqweeqwsqweawedqweqweqweawerwaerwearwerw.com', 'secret');
        $this->assertResponseValidation($response,422,'email','The email must be a valid email address.');

        $user = factory(User::class)->create();
        $response = $this->post_user_creation($faker->name, $user->email, 'secret');
        $this->assertResponseValidation($response,422,'email','The email has already been taken.');


    }
    /**
     * Assert Response validation.
     */
    private function assertResponseValidation($response, $statusCode, $field, $email){
        $response->assertStatus($statusCode)->assertExactJson([
            $field => [
                0 => $email
            ]
        ]);
    }


    /**
     * Api create user.
     *
     * @test
     */
    public function api_create_user()
    {
        $faker = Factory::create();

        $this->publishFactories();
        $this->signInAsUserManager('api');
        Event::fake();
        $response = $this->post_user_creation($name = $faker->name,
            $email = $faker->unique()->safeEmail, 'secret');

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email
        ]);

        Event::assertDispatched(UserCreated::class, function ($e) use ($name,$email) {
            return $e->user->name === $name && $e->user->email === $email;
        });

        $response->assertStatus(200)
            ->assertExactJson([ 'created' => true ]);

    }

    /**
     * ########## INVITATIONS
     */

    /**
     * Check app already have send-user-invitations permission.
     *
     * @test
     */
    public function check_app_already_have_send_user_invitations_permission()
    {
        $this->seedPermissions();
        $this->assertPermissionExists('send-user-invitations');
    }

    /**
     * Guest users cannot sent user invitations.
     *
     * @test
     */
    public function guest_users_cannot_sent_user_invitations()
    {
        $response = $this->post('/api/v1/management/users/invitations/send');

        $response->assertStatus(302);
    }

    /**
     * Users without authorization cant sent user invitations.
     *
     * @test
     */
    public function users_without_authorization_cant_sent_user_invitations()
    {
        $this->signIn(null,'api');
        $response = $this->post('/api/v1/management/users/invitations/send');
        $response->assertStatus(403);
    }

    /**
     * Api check email validation with new user invitations.
     *
     * @test
     */
    public function api_check_email_validations_with_new_users_invitations()
    {
        $this->signInAsUserManager('api');
        $response = $this->post_user_invitation([ 'email' => '']);
        $this->assertResponseValidationEmail($response,422,'The email field is required.');

        $response = $this->post_user_invitation([ 'email' => 'invalidformat']);
        $this->assertResponseValidationEmail($response,422,'The email must be a valid email address.');

        $user = factory('App\User')->create();
        $response = $this->post_user_invitation([ 'email' => $user->email]);
        $this->assertResponseValidationEmail($response,422,'The email has already been taken.');

        $response = $this->post_user_invitation(['email' => 'sdsfsdfsfdsdfe@dadddaasdasdaseqwerqwqqweqwqwewqeqweeqwsqweawedqweqweqweawerwaerwearwerw.com']);
        $this->assertResponseValidationEmail($response,422,'The email must be a valid email address.');

    }

    /**
     * Post user invitations.
     *
     * @param $data
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function post_user_invitation($data){
        return $this->json('POST','/api/v1/management/users/invitations/send', $data);
    }

    /**
     * Assert Response validation email.
     */
    private function assertResponseValidationEmail($response, $statusCode, $email){
        $response->assertStatus($statusCode)->assertExactJson([
            'email' => [
                0 => $email
            ]
        ]);
    }

    /**
     * Api creates a new invitation.
     *
     * @test
     */
    public function api_creates_a_new_invitation()
    {
        $this->signInAsUserManager('api');
        $faker = Factory::create();

        $response = $this->post_user_invitation(['email' => $email = $faker->email]);

        $response->assertStatus(200)
            ->assertJson([
                'created' => true,
            ]);

        $this->assertDatabaseHas('user_invitations', [
            'email' => $email,
            'state' => 'pending'
        ]);
    }

    /**
     * Mail is sent when api creates a new user invitation.
     *
     * @test
     */
    public function mail_is_sent_when_api_creates_a_new_user_invitation()
    {
        $this->signInAsUserManager('api');

        $faker = Factory::create();
        Mail::fake();

        $response = $this->post_user_invitation(['email' => $email = $faker->email]);

        Mail::assertSent(UserInvitation::class, function ($mail) use ($email) {
            return $mail->invitation->email = $email &&
                $mail->hasTo($email);
            $mail->hasFrom(config('mail.from')['address']);
        });
    }


    /**
     * userInvited events is fired when api creates a new invitation.
     *
     * @test
     */
    public function user_invited_event_is_fired_when_api_creates_a_new_invitation()
    {
        $this->signInAsUserManager('api');
        $faker = Factory::create();

        Event::fake();
        //So forced to indicate initial state and token
        $response = $this->post_user_invitation([
            'email' => $email = $faker->email,
            'state' => 'pending',
            'token' => hash_hmac('sha256', str_random(40), env('APP_KEY'))
        ]);
        Event::assertDispatched(UserInvited::class, function ($e) use ($email) {
            return $e->invitation->email === $email && $e->invitation->state === 'pending';
        });
    }

    /**
     * Guest users cannot see user invitations.
     *
     * @test
     */
    public function guest_users_cannot_see_user_invitations()
    {
        $response = $this->json('GET','/api/v1/management/users/invitations');
        $response->assertStatus(401);
    }

    /**
     * Users without authorization cannot see user invitations.
     *
     * @test
     */
    public function users_without_authorization_cannot_see_user_invitations()
    {
        $this->signIn(null,'api');
        $response = $this->json('GET','/api/v1/management/users/invitations');
        $response->assertStatus(403);
    }

    /**
     * Delete user invitation.
     *
     * @param $id
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function delete_user_invitation($id) {
        return $this->json('DELETE','api/v1/management/users/invitations/' . $id);
    }

    /**
     * Unauthenticated users cannot delete user invitations.
     *
     * @test
     */
    public function unauthenticated_users_cannot_delete_user_invitations() {
        $response = $this->delete_user_invitation(1);
        $response->assertStatus(401);
    }

    /**
     * Unauthorized users cannot delete user invitations.
     *
     * @test
     */
    public function unauthorized_users_cannot_delete_user_invitations() {
        $this->signIn(null,'api');
        $response = $this->delete_user_invitation(1);
        $response->assertStatus(403);
    }

    /**
     * Api deletes user invitations.
     *
     * @test
     */
    public function api_deletes_user_invitations() {

        $invitation = $this->createUserInvitations();

        $this->signInAsUserManager('api');

        $response = $this->delete_user_invitation($invitation->id);

        $response->assertStatus(200)
            ->assertJson([
                'deleted' => true,
            ]);

        $this->assertDatabaseMissing('user_invitations', [
            'id' => $invitation->id
        ]);
    }

    /**
     * Show user invitation.
     *
     * @param $id
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function show_user_invitation($id) {
        return $this->json('GET','api/v1/management/users/invitations/' . $id);
    }

    /**
     * Unauthenticated users cannot show user invitations.
     *
     * @test
     */
    public function unauthenticated_users_cannot_show_user_invitations() {
        $response = $this->show_user_invitation(1);
        $response->assertStatus(401);
    }

    /**
     * Unauthorized users cannot show user invitations.
     *
     * @test
     */
    public function unauthorized_users_cannot_show_user_invitations() {
        $this->signIn(null,'api');
        $response = $this->show_user_invitation(1);
        $response->assertStatus(403);
    }

    /**
     * Edit user invitation.
     *
     * @param $id
     * @param array $data
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    private function edit_user_invitation($id , $data) {
        return $this->json('PUT','api/v1/management/users/invitations/' . $id , $data);
    }

    /**
     * Unauthenticated users cannot edit user invitations.
     *
     * @test
     */
    public function unauthenticated_users_cannot_edit_user_invitations() {
        $response = $this->edit_user_invitation(1,[]);
        $response->assertStatus(401);
    }

    /**
     * Unauthorized users cannot edit user invitations.
     *
     * @test
     */
    public function unauthorized_users_cannot_edit_user_invitations() {
        $this->signIn(null,'api');
        $response = $this->edit_user_invitation(1, []);
        $response->assertStatus(403);
    }

    /**
     * Api show user invitations.
     *
     * @test
     */
    public function api_show_user_invitations() {

        $invitation = $this->createUserInvitations();

        $this->signInAsUserManager('api');
        $faker = Factory::create();
        $response = $this->edit_user_invitation($invitation->id, [ 'email' => $faker->email]);
        $response->assertStatus(200)
            ->assertJson([
                'updated' => true,
            ]);

    }

    /**
     * Create user invitations.
     *
     * @param null $num
     * @return mixed
     */
    private function createUserInvitations($num = null) {
        return factory(UserInvitationModel::class,$num)->create();
    }
    /**
     * Api list user invitations
     *
     * @test
     */
    public function api_list_user_invitations()
    {
        $this->publishFactories();
        $this->createUserInvitations(10);
        $this->signInAsUserManager('api');
        $response = $this->json('GET','/api/v1/management/users/invitations');
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [
            '*' => [
                'id', 'email', 'state','token','user_id','created_at','updated_at'
            ]
        ]]);
    }

    /**
     * Assert role exists in database.
     *
     * @param $permission
     */
    private function assertPermissionExists($permission)
    {
        $this->assertInstanceOf(Permission::class, $p = Permission::findByName($permission));
        $this->assertEquals($permission , $p->name);
        $this->assertDatabaseHas('permissions', [
            'name' => $permission
        ]);
    }

    /**
     * Assert assertRoleExists exists in database.
     *
     * @param $role
     */
    private function assertRoleExists($role)
    {
        $this->assertInstanceOf(Role::class, $p = Role::findByName($role));
        $this->assertEquals($role , $p->name);
        $this->assertDatabaseHas('roles', [
            'name' => $role
        ]);
    }

    /**
     * Seed permissions.
     */
    private function publishFactories()
    {
        //Publish factories
        $this->artisan('vendor:publish', ['--provider' => 'Acacha\Users\Providers\UsersManagementServiceProvider', '--tag' => 'acacha_users_factories']);
    }

    /**
     * Seed permissions.
     */
    private function seedPermissions()
    {
        //Publish seed
        $this->artisan('vendor:publish', ['--provider' => 'Acacha\Users\Providers\UsersManagementServiceProvider', '--tag' => 'acacha_users_seeds']);
        //Execute seed
        $this->artisan('db:seed',['--class' => 'CreateUsersManagementPermissions']);
    }
}
