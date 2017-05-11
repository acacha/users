<?php

namespace Tests\Unit;

use Acacha\Users\Models\UserInvitation;
use Faker\Factory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class UserInvitationTest.
 *
 * @package Tests\Unit
 */
class UserInvitationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function an_user_invitation_is_created_correctly()
    {
        $faker = Factory::create();
        $invitation = UserInvitation::create(['email' => $faker->email ]);
        $this->assertInstanceOf(UserInvitation::class, $invitation);
        $this->assertTrue($invitation->pending());
        $this->assertFalse($invitation->accepted());
    }
}
