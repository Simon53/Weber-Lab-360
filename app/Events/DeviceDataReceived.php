<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeviceDataReceived implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function broadcastOn()
    {
        // Channel name হবে machine.{mac_id}
        // Sanitize MAC to remove characters not allowed in channel names (e.g. ':')
        $mac = isset($this->data['mac_id']) ? $this->data['mac_id'] : '';
        $safeMac = str_replace(':', '', $mac);
        return new Channel('machine.' . $safeMac);
    }

    public function broadcastAs()
    {
        return 'device.data';
    }
}

