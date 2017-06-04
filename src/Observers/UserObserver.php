<?php

namespace Acacha\Users\Observers;

use App\User;
use Venturecraft\Revisionable\Revision;

/**
 * Class UserObserver.
 */
class UserObserver
{
    /**
     * Listen to the User deleted event.
     */
    public function deleted(User $user)
    {
        \DB::table((new Revision())->getTable())->insert([
            [
                'revisionable_type' => $user->getMorphClass(),
                'revisionable_id' => $user->id,
                'key' => 'deleted_at',
                'old_value' => $user,
                'new_value' => new \DateTime(),
                'user_id' => (\Auth::check() ? \Auth::user()->id : null),
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ]
        ]);
    }
}