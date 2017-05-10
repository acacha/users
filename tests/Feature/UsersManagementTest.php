<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
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
     * Unauthorized user cannnot browse users management.
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
        $this->get('/api/management/users')
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
        $this->json('GET','/api/management/users')
            ->assertStatus(401);
    }


    /**
     * Api show an user for authorized users correctly.
     *
     * @test
     */
    public function api_show__an_user_for_authorized_users_correctly()
    {
        $user = factory('App\User')->create();
        $this->actingAs($user,'api')
            ->json('GET', '/api/management/users')
            ->assertStatus(200)

            ->assertExactJson([
                'current_page' => 1,
                'data' => [
                    ['id'=> $user->id,
                    'name' =>  $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at->toDateTimeString(),
                    'updated_at' => $user->updated_at->toDateTimeString()
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
        $this->actingAs($user[0],'api')
            ->json('GET','/api/management/users')
            ->assertJsonStructure(['data' => [
                    '*' => [
                        'id', 'name', 'email','created_at','updated_at'
                ]
            ]]);
    }

}
