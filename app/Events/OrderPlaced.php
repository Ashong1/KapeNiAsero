<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // <--- IMPORTANT
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    // Pass the order data to the event
    public function __construct(Order $order)
    {
        // Load the items and products so the kitchen sees the names immediately
        $this->order = $order->load('items.product');
    }

    // Broadcast on a public channel named 'kitchen'
    public function broadcastOn()
    {
        return [
            new Channel('kitchen'),
        ];
    }
}