<?php

namespace Tests\Feature;

use App;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

/**
 * Class LoggedUserTest.
 *
 * @package Tests\Feature
 */
class LoggedUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up.
     */
    public function setUp()
    {
        parent::setUp();
        App::setLocale('en');
//        $this->withoutExceptionHandling();
    }

    /**
     * User can obtain own info
     *
     * @test
     * @return void
     */
    public function user_can_obtain_own_info()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('GET','/api/v1/user');
        $response->assertSuccessful();
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'updated_at' => $user->updated_at,
            'created_at' => $user->created_at,
        ]);
    }

    /**
     * User can update own info.
     *
     * @test
     * @return void
     */
    public function user_can_update_own_info()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('PUT','/api/v1/user',[
            'name' => 'Pepito Palotes',
            'email' => 'pepitopalotes@gmail.com',
            'password' => 'secret'
        ]);
        $response->assertSuccessful();
        $response->assertJson([
            'id' => $user->id,
            'name' => 'Pepito Palotes',
            'email' => 'pepitopalotes@gmail.com'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Pepito Palotes',
            'email' => 'pepitopalotes@gmail.com'
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
    }
}
