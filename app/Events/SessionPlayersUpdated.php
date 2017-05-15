<?php

namespace App\Events;

use App\Session;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SessionPlayersUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    private $session;
    private $channelName;
    public $players;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->players = $this->session->players;
        $this->channelName = "session-" . $this->session->id;
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
