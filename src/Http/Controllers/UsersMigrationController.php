<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Http\Requests\StopMigrationBatchRequest;
use Acacha\Users\Http\Requests\UsersMigrationRequest;
use Acacha\Users\Jobs\MigrateUsers;
use Acacha\Users\Models\ProgressBatch;
use Acacha\Users\Models\UserMigration;
use Acacha\Users\Services\MigrationBatch;
use Illuminate\Http\Request;
use Redis;
use Scool\EbreEscoolModel\User;
use App\User as UserOnDestination;

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
        return [ 'data' => User::all()->count() ];
    }

    /**
     * Get total number of migrated users in source migration database.
     *
     * @return array
     */
    public function totalNumberOfMigratedUsers()
    {
        $this->authorize('migrate-users');
        $ids = UserMigration::all()->pluck('source_user_id');
        return [ 'data' => ($ids->count() > 0) ? User::whereIn('id',$ids)->count() : 0 ];

    }

    /**
     * Get total number of users in source migration database.
     */
    public function totalNumberOfUsersOnDestination()
    {
        $this->authorize('migrate-users');
        return [ 'data' => UserOnDestination::all()->count() ];
    }

    /**
     * @return array
     */
    public function totalNumberOfMigratedUsersOnDestination()
    {
        $this->authorize('migrate-users');
        return [ 'data' => UserOnDestination::migrated()->count() ];
    }

    /**
     * Check connection.
     *
     * @return array
     */
    public function checkConnection(Request $request)
    {
        $this->authorize('migrate-users');
        $connection = $request->has('connection')
            ?  $request->has('connection')
            : config('users.source_database_connection_name');
        if (check_connection($connection)) return [ 'connected' => true ];
        return [ 'connected' => false ];
    }

    /**
     * Migrate multiple users.
     *
     */
    public function migrate(UsersMigrationRequest $request, MigrationBatch $batchService)
    {
        $batch = $batchService->init();
        $usersToMigrate = $this->usersToMigrateByRequest($request);

        //TODO cal?
        $this->clearUserMigrationsJobsQueue();
//        dispatch((new MigrateUsers($usersToMigrate, $batch))->onQueue('users-migration'));
        dispatch((new MigrateUsers($usersToMigrate, $batch)));

        return $batch;

    }

    /**
     * Clear user migrations job queue.
     *
     */
    protected function clearUserMigrationsJobsQueue() {
        Redis::connection()->del('queues:users-migration');
    }

    /**
     * Stop migration batch request.
     */
    public function stopMigration(StopMigrationBatchRequest $request)
    {
        $batch = ProgressBatch::findOrFail($request->input('batch_id'));
        $batch->stop();
        $batch->save();
        $this->clearUserMigrationsJobsQueue();
        return [ 'stopped' => true ];
    }

    /**
     * Resume migration.
     */
    public function resumeMigration()
    {
        $this->authorize('migrate-users');
        $this->resumeMigrationBatch();
    }

    /**
     * Resume migration batch
     */
    protected function resumeMigrationBatch()
    {
        
    }

    /**
     * Get users migration history.
     *
     * @return array
     */
    public function history()
    {
        $this->authorize('migrate-users');
        return UserMigration::orderBy('created_at', 'desc')->paginate();
    }

    /**
     * Get users migration batch history.
     *
     * @return array
     */
    public function batchHistory()
    {
        $this->authorize('migrate-users');
        return ProgressBatch::orderBy('created_at', 'desc')->get();
    }

    /**
     * Get users to migrate by request.
     *
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection|void|static[]
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
     * Switch default connection.
     *
     * @param $connection
     * @param $env
     */
    protected function switchConnection($env,$connection)
    {
        config(['database.default' => env($env, $connection)]);
    }

    /**
     * Dump current connection.
     */
    protected function dumpCurrentConnection()
    {
        dump(config('database.default'));
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
        return \Scool\EbreEscoolModel\User::with('person')->get();
    }
}
