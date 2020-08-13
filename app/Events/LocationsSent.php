<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class LocationsSent extends Notification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rideId;
    public $currentUser;
    public $locationLatitude;
    public $locationLongitude;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($rideId,$currentUser,$locationLatitude,$locationLongitude)
    {
        $this->rideId = $rideId;
        $this->currentUser = $currentUser;
        $this->locationLatitude = $locationLatitude;
        $this->locationLongitude = $locationLongitude;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('ride.'.$this->rideId);
    }
}
