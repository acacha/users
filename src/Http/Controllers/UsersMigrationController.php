<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Events\UserMigrationStatusUpdated;
use Acacha\Users\Http\Requests\UsersMigrationRequest;

/**
 * Class UsersMigrationController.
 *
 * @package App\Http\Controllers
 */
class UsersMigrationController extends Controller
{
    /**
     * Show users migration.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('migrate-users');
        $data = [];
        return view('acacha_users::users-migration',$data);
    }

    /**
     * Get total number of users in source migration database.
     */
    public function totalNumberOfUsers()
    {
        $this->authorize('migrate-users');
        return [ 'data' => 68 ];
    }

    /**
     * Migrate multiple users.
     *
     */
    public function migrate(UsersMigrationRequest $request)
    {
        $usersToMigrate = $this->usersToMigrateByRequest($request);
        dd($usersToMigrate);
        $progress= 0;
        foreach ($usersToMigrate as $user ) {
            event(new UserMigrationStatusUpdated($user, $progress));
            sleep(1);
            dump ('User ' . $user . ' migrated!');
            $progress = $progress + 50;
        }
        event(new UserMigrationStatusUpdated('Finish!', $progress));
    }


    /**
     * Get users to migrate.
     *
     * @param $request
     */
    protected function usersToMigrateByRequest($request)
    {
        if ($users = $request->has('users')) return $this->usersToMigrate($users, $request);
        if ($filter = $request->has('filter')) return $this->usersToMigrateByFilter($filter, $request);
        return $this->allUsers($request);
    }

    /**
     * Users to migrate by user id list.
     *
     * @param $users
     */
    protected function usersToMigrate($users, $request)
    {

    }

    /**
     * Users to migrate by filter.
     *
     * @param $filter
     */
    protected function usersToMigrateByFilter($filter, $request)
    {

    }

    /**
     * Get all valid users.
     *
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function allUsers($request)
    {
        return \Scool\EbreEscoolModel\User::all();
    }
}
