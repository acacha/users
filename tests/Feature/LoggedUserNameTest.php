<?php

namespace Tests\Feature;

use App;
use App\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

/**
 * Class LoggedUserNameTest.
 *
 * @package Tests\Feature
 */
class LoggedUserNameTest extends TestCase
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
     * Can update name.
     *
     * @test
     * @return void
     */
    public function can_update_name()
    {
        $user = factory(User::class)->create();
        $originalUserName = $user->name;
        $this->actingAs($user, 'api');

        $response = $this->json('PUT','/api/v1/user/name',[
            'name' => 'Pepito Palotes'
        ]);
        $response->assertSuccessful();
        $response->assertJson([
            'id' => $user->id,
            'name' => 'Pepito Palotes',
            'email' => $user->email
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Pepito Palotes',
            'email' => $user->email
        ]);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $originalUserName,
            'email' => $user->email
        ]);
    }
}
