<?php

namespace Acacha\Users\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class UserMigrationStatusUpdated
 *
 * @package Acacha\Users\Events
 */
class UserMigrationStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Migrated user.
     *
     * @var
     */
    public $user;

    /**
     * Progress status value.
     *
     * @var
     */
    public $progress;

    /**
     * Create a new event instance.
     *
     * @param $user
     */
    public function __construct($user, $progress)
    {
        $this->user = $user;
        $this->progress = $progress;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('users-migration');
    }
}
