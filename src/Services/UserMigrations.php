<?php

namespace Acacha\Users\Services;

use Acacha\Users\Events\ProgressBarStatusHasBeenUpdated;
use Acacha\Users\Models\ProgressBatch;
use Illuminate\Support\Collection;
use Psr\Log\InvalidArgumentException;
use Scool\EbreEscoolModel\User;
use Acacha\Users\Events\UserHasBeenMigrated;
use Acacha\Users\Exceptions\MigrationException;
use Illuminate\Database\QueryException;

/**
 * Class UserMigrations.
 *
 * @package Acacha\Users\Services
 */
class UserMigrations
{

    /**
     * Migrate users.
     *
     * @param array|User $usersToMigrate
     * @param $batch
     */
    public function migrate($usersToMigrate, $batch)
    {
        if (is_array($usersToMigrate) || $usersToMigrate instanceof Collection) {
            $this->migrateUsers($usersToMigrate, $batch);
            return;
        }
        if ($usersToMigrate instanceof User) {
            $this->migrateUser($usersToMigrate, $batch->id);
            return;
        }
        throw new InvalidArgumentException();
    }

    /**
     * Migrate users.
     *
     * @param $usersToMigrate
     * @param $batch
     */
    public function migrateUsers($usersToMigrate, $batch)
    {
        $max_execution_time = ini_get('max_execution_time');
        set_time_limit ( 0);

        if (count($usersToMigrate) == 0 ) return;

        info('Users migration : A new batch user migration ( id: ' . $batch->id .') has been initialized!');
        $progressIncrement = $this->progressIncrement($usersToMigrate);

        $progress= 0; $migratedUsers=0; $errorUsers=0;

        foreach ($usersToMigrate as $user) {
            if ($batch->stopped()) {
                info('Users migration : A batch user migration ( id: ' . $batch->id .') has been stopped!');
                event(new ProgressBarStatusHasBeenUpdated('users-migration-progress-bar', $progress,'Migration stopped!' ));
                return;
            }
            info('Users migration : Migrating user username: ' . $user->username .'!');
            event(new ProgressBarStatusHasBeenUpdated('users-migration-progress-bar',$progress,'Migrating user ' . $user->username));
            try {
                $this->migrateUser($user, $batch->id);
                event(new ProgressBarStatusHasBeenUpdated('users-migration-progress-bar', $progress,'User ' . $user->username . ' migrated' ));
                $migratedUsers++;
                info('Users migration : User username: ' . $user->username .' migrated correctly!');
            } catch (MigrationException $e) {
                event(new ProgressBarStatusHasBeenUpdated('users-migration-progress-bar', $progress,'User ' . $user->username . ' not migrated. ' . $e->getMessage() ));
                $errorUsers++;
                info('Users migration : ERROR migrating user username: ' . $user->username .'!');
            }
            $progress= $progress + $progressIncrement;
        }

        event(new ProgressBarStatusHasBeenUpdated('users-migration-progress-bar', 100,'Migration finished!' ));
        $this->finishMigrationBatch($batch, $migratedUsers,$errorUsers);
        info('Users migration : A batch user migration ( id: ' . $batch->id .') has been finished!');
        set_time_limit ( $max_execution_time);
    }

    /**
     * Migrate User.
     *
     * @param $user
     * @param $batchId
     * @return null
     * @throws MigrationException
     */
    public function migrateUser($user, $batchId)
    {
        $newUser = null;
        if ($email = $user->getEmailFromUser()) {
            try {
                $newUser = \App\User::create([
                    'name' => $user->username,
                    'email' => $email,
                    'password' => bcrypt('secret')
                ]);
                event(new UserHasBeenMigrated($user->id, $user->toJson(), $newUser, $batchId));
                return $newUser;
            } catch( QueryException $e) {
                event(new UserHasBeenMigrated($user->id, $user->toJson(), null, $batchId));
                throw new MigrationException($e->getMessage());
            }
        } else {
            event(new UserHasBeenMigrated($user->id, $user->toJson(), null, $batchId));
            throw new MigrationException("Source User doesn't have email");
        }
    }

    /**
     * Finish migration batch.
     *
     * @param ProgressBatch $batch
     * @param $migratedUsers
     * @param $errorUsers
     */
    protected function finishMigrationBatch(ProgressBatch $batch , $migratedUsers, $errorUsers)
    {
        $batch->accomplished = $migratedUsers;
        $batch->incidences = $errorUsers;
        $batch->finish();
        $batch->save();
    }

    /**
     * Calculate progress increment.
     *
     * @return float|int
     */
    protected function progressIncrement($usersToMigrate)
    {
        return 100/count($usersToMigrate);
    }
}