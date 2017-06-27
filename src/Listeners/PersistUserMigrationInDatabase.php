<?php

namespace Acacha\Users\Listeners;

use Acacha\Users\Events\UserHasBeenMigrated;
use Acacha\Users\Events\UserMigrationHasBeenPersisted;
use Acacha\Users\Models\UserMigration;

/**
 * Class PersistUserMigrationInDatabase.
 *
 */
class PersistUserMigrationInDatabase
{
    /**
     * PersistUserMigrationInDatabase constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserHasBeenMigrated  $event
     * @return void
     */
    public function handle(UserHasBeenMigrated $event)
    {
        try {
            $newUserMigration = UserMigration::create([
                'source_user_id' => $event->sourceUserId,
                'source_user' => $event->sourceUser,
                'user_id' => $event->newUser ? $event->newUser->id : null,
                'user_migration_batch_id' => $event->user_migration_batch_id
            ]);
            event(new UserMigrationHasBeenPersisted($newUserMigration));
        } catch (\Exception $e) {
            dump($e->getMessage());
        }

    }

}