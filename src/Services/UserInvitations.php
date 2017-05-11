<?php

namespace Acacha\Users\Services;
use Acacha\Users\Mail\UserInvitation;
use Acacha\Users\Models\UserInvitation as UserInvitationModel;
use Mail;

/**
 * Class UserInvitations.
 *
 * @package Acacha\Users\Services
 */
class UserInvitations
{
    /**
     * Send user invitation.
     *
     * @param UserInvitationModel $invitation
     */
    public function send(UserInvitationModel $invitation)
    {
        Mail::to($invitation->email)->send(new UserInvitation($invitation));
    }
}