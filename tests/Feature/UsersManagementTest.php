<?php

namespace Tests\Feature;

use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Spatie\Permission\Models\Permission;
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
    public function check_app_already_have_manage_users_permission()
    {
        $this->seedPermissions();
        $this->assertPermissionExists('manage-users');
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
        $user = factory('App\User',10)->create();
        $this->signInAsUserManager('api')
            ->json('GET','/api/v1/management/users')
            ->assertJsonStructure(['data' => [
                    '*' => [
                        'id', 'name', 'email','created_at','updated_at'
                ]
            ]]);
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
        $response = $this->post('/api/v1/management/users/send/invitation');

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
        $response = $this->post('/api/v1/management/users/send/invitation');
        $response->assertStatus(403);
    }

    /**
     * Api creates a new invitation.
     *
     * @test
     */
    public function api_creates_a_new_invitation()
    {
        $this->signInAsUserManager('api');
        $response = $this
            ->post('/api/v1/management/users/send/invitation');

        $response->assertStatus(200);
    }

    /**
     * Guest users cannot see user invitations.
     * @group failing
     * @test
     */
    public function guest_users_cannot_see_user_invitations()
    {
        $response = $this->post('/api/v1/management/users/send/invitation');

        $response->assertStatus(302);
    }

    /**
     * Users without authorization cant see user invitations.
     * @group failing
     * @test
     */
    public function users_without_authorization_cant_see_user_invitations()
    {
        $this->signIn(null,'api');
        $response = $this->post('/api/v1/management/users/send/invitation');
        $response->assertStatus(403);
    }


    /**
     * Assert permission exists in datadase.
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
