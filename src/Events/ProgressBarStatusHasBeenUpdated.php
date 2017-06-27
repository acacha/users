<?php

namespace Acacha\Users\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class ProgressBarStatusHasBeenUpdated
 *
 * @package Acacha\Users\Events
 */
class ProgressBarStatusHasBeenUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Id of progress bar to update.
     *
     * @var
     */
    public $id;

    /**
     * Current progress var value.
     *
     * @var
     */
    public $progress;

    /**
     * Message to show in progress bar.
     *
     * @var
     */
    public $message;

    /**
     * Broadcast channel name.
     *
     * @var string
     */
    protected $channelName = 'progress-bar';

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
     * ProgressBarStatusHasBeenUpdated constructor.
     *
     * @param $progress
     * @param $message
     */
    public function __construct($id, $progress, $message)
    {
        $this->id = $id;
        $this->progress = $progress;
        $this->message = $message;
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
