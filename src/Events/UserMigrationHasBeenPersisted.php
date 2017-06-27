<?php

namespace Acacha\Users\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class UserMigrationHasBeenPersisted
 *
 * @package Acacha\Users\Events
 */
class UserMigrationHasBeenPersisted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User migration model.
     *
     * @var
     */
    public $userMigration;

    /**
     * Broadcast channel name.
     *
     * @var string
     */
    protected $channelName = 'users-migration';

    /**
     * @return string
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * @param string $channelName
     */
    public function setChannelName($channelName)
    {
        $this->channelName = $channelName;
    }

    /**
     * UserMigrationhasBeenPersisted constructor.
     *
     * @param $userMigration
     */
    public function __construct($userMigration)
    {
        $this->userMigration = $userMigration;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel($this->channelName);
    }
}
