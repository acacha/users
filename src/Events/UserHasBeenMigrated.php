<?php

namespace Acacha\Users\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class UserHasBeenMigrated
 *
 * @package Acacha\Users\Events
 */
class UserHasBeenMigrated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Source user id.
     *
     * @var
     */
    public $sourceUserId;

    /**
     * Source user.
     *
     * @var
     */
    public $sourceUser;

    /**
     * New user.
     *
     * @var
     */
    public $newUser;

    /**
     * User migration batch id.
     *
     * @var
     */
    public $user_migration_batch_id;

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
     * UserHasBeenMigrated constructor.
     *
     * @param $sourceUserId
     * @param $sourceUser
     * @param $newUser
     * @param $user_migration_batch_id
     */
    public function __construct($sourceUserId, $sourceUser, $newUser, $user_migration_batch_id)
    {
        $this->sourceUserId = $sourceUserId;
        $this->sourceUser = $sourceUser;
        $this->newUser = $newUser;
        $this->user_migration_batch_id = $user_migration_batch_id;
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
