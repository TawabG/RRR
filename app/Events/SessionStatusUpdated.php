<?php

namespace App\Events;

use App\Session;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SessionStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $session;
    private $channelName;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->channelName = "session-" . $session->id;
    }

    /**
     * @author Casper Schobers
     *
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel($this->channelName);
    }
}
