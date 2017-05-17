<?php

namespace Acacha\Users\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class UserCreated.
 *
 * @package Acacha\Users\Events
 */
class UserCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    /**
     * UserCreated constructor.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
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
