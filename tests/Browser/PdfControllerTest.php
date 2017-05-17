<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class PdfControllerTest.
 *
 * @package Tests\Browser
 */
class PdfControllerTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * test users are converted to pdf correctly.
     * @return void
     */
    public function test_users_are_converted_to_pdf_correctly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/users/pdf')
                    ->assertSee('todo');
        });
    }

    /**
     * test users are converted to pdf correctly.
     *
     * @group failing1
     * @return void
     */
    public function test_users_are_show_correctly()
    {
        //Prepare
        $users = $this->createUsers(25);
        //Execute

        //Assert
        $this->browse(function (Browser $browser) use ($users){
            $browser->visit('/users/pdf/view')
                    ->assertTitle('Users List');
            //CSS Selectors
            $this->assertEquals(2, count($browser->elements('div#users-list table#users-tablelist tr th')));
            $this->assertEquals(25, count($browser->elements('div#users-list table#users-tablelist tr')));

        });
    }

    /**
     * test a user is converted to pdf correctly.
     *
     *
     * @return void
     */
    public function test_user_is_show_correctly()
    {
        //Prepare
        $user = $this->createUsers();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/user/pdf/' . $user->id)
                ->assertSee($user->name)
                ->assertSee($user->email);
        });

    }

    /**
     * Create users.
     * @param null $num
     * @return mixed
     */
    private function createUsers($num = null)
    {
        return factory(User::class,$num)->create();
    }

    /**
     * test a user is converted to pdf correctly.
     *
     *
     * @return void
     */
    public function test_user_is_converted_to_pdf_correctly()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/user/pdf/1');

        });

    }


}
