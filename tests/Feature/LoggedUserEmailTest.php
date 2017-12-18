<?php

namespace Tests\Feature;

use App;
use App\User;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

/**
 * Class LoggedUserEmailTest.
 *
 * @package Tests\Feature
 */
class LoggedUserEmailTest extends TestCase
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
     * Can update email.
     *
     * @test
     * @return void
     */
    public function can_update_email()
    {
        $password = bcrypt('passw0rd');
        $user = factory(User::class)->create([
            'password' => $password
        ]);
        $originalEmail = $user->email;
        $this->actingAs($user, 'api');

        $response = $this->json('PUT','/api/v1/user/email',[
            'current_password' => 'passw0rd',
            'email' => 'pepitopalotes@gmail.com'
        ]);
        $response->assertSuccessful();
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => 'pepitopalotes@gmail.com'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => 'pepitopalotes@gmail.com'
        ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $originalEmail
        ]);
    }

    /**
     * Can not update email.
     *
     * @test
     * @return void
     */
    public function can_not_update_email()
    {
        $password = bcrypt('passw0rd');
        $user = factory(User::class)->create([
            'password' => $password
        ]);
        $this->actingAs($user, 'api');

        $response = $this->json('PUT','/api/v1/user/email',[
            'current_password' => 'incorrecta',
            'email' => 'pepitopalotes@gmail.com'
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
