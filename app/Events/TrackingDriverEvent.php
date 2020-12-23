<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrackingDriverEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $service_id;
    public $vehicle;

    public function __construct($service_id, $vehicle)
    {
        $this->service_id = $service_id;
        $this->vehicle = $vehicle;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('channelTrackingDriver-'.$this->service_id);
    }

    public function broadcastAs(){
        return 'trackingDriver';
    }

    public function broadcastWith(){
        return ['vehicle' => $this->vehicle];
    }

}
