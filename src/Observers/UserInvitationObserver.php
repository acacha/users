<?php

namespace Acacha\Users\Observers;

use Acacha\Users\Models\UserInvitation;
use Acacha\Users\Services\UserInvitations;
use Venturecraft\Revisionable\Revision;

/**
 * Class UserInvitationObserver.
 */
class UserInvitationObserver
{

    public $service;

    /**
     * UserInvitationObserver constructor.
     *
     * @param $service
     */
    public function __construct(UserInvitations $service)
    {
        $this->service = $service;
    }

    /**
     * Listen to the UserInvitation created event.
     *
     * @param  UserInvitation  $invitation
     * @return void
     */
    public function created(UserInvitation $invitation)
    {
        $this->service->send($invitation);
    }

    /**
     * Listen to the UserInvitation deleting event.
     *
     * @param  UserInvitation  $invitation
     * @return void
     */
    public function deleting(UserInvitation $invitation)
    {
        //
    }

    /**
     * Listen to the UserInvitation deleted event.
     */
    public function deleted(UserInvitation $invitation)
    {
        // Insert into 'revisions' (calling getTable probably not necessary, but to be safe).
        \DB::table((new Revision())->getTable())->insert([
            [
                'revisionable_type' => $invitation->getMorphClass(),
                'revisionable_id' => $invitation->id,
                'key' => 'deleted_at',
                'old_value' => null,
                'new_value' => new \DateTime(),
                'user_id' => (\Auth::check() ? \Auth::user()->id : null),
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ]
        ]);
    }
}