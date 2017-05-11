<?php

namespace Acacha\Users\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Acacha\Users\Models\UserInvitation as UserInvitationModel;

/**
 * Class UserInvitation.
 *
 * @package Acacha\Users\Mail
 */
class UserInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invitation;

    /**
     * UserInvitation constructor.
     *
     * @param UserInvitationModel $invitation
     */
    public function __construct(UserInvitationModel $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('acacha_users::emails.users.invitation');
    }
}
