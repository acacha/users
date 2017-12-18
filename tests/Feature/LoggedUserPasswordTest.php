<?php

namespace Tests\Feature;

use App;
use App\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

/**
 * Class LoggedUserPasswordTest.
 *
 * @package Tests\Feature
 */
class LoggedUserPasswordTest extends TestCase
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
     * Can update password.
     *
     * @test
     * @return void
     */
    public function can_update_password()
    {
        $password = bcrypt('passw0rd');
        $user = factory(User::class)->create([
            'password' => $password
        ]);
        $this->actingAs($user, 'api');

        $response = $this->json('PUT','/api/v1/user/password',[
            'current_password' => 'passw0rd',
            'password' => 'secret'
        ]);
        $response->assertSuccessful();
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);

        $user = User::findOrFail($user->id);
        $this->assertTrue(Hash::check('secret', $user->password));
    }

    /**
     * Can not update password.
     *
     * @test
     * @return void
     */
    public function can_not_update_password()
    {
        $password = bcrypt('passw0rd');
        $user = factory(User::class)->create([
            'password' => $password
        ]);
        $this->actingAs($user, 'api');

        $response = $this->json('PUT','/api/v1/user/password',[
            'current_password' => 'incorrecta',
            'password' => 'secret'
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'current_password' => [
                    0 => "The password is not valid"
                ]
            ]
        ]);
    }
}
