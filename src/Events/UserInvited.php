<?php

namespace Acacha\Users\Events;

use Acacha\Users\Models\UserInvitation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class UserInvited.
 *
 * @package App\Events
 */
class UserInvited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invitation;

    /**
     * UserInvited constructor.
     *
     * @param $invitation
     */
    public function __construct(UserInvitation $invitation)
    {
        $this->invitation = $invitation;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('acacha-users');
    }
}
