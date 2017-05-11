<?php

namespace Acacha\Users\Observers;

use Acacha\Users\Models\UserInvitation;
use Acacha\Users\Services\UserInvitations;

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
}