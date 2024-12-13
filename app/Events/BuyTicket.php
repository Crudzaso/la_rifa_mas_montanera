<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Raffle;

class BuyTicket
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $raffle;
    public $tickets;
    public $totalPrice;

    public function __construct(User $user, Raffle $raffle, array $tickets, float $totalPrice)
    {
        $this->user = $user;
        $this->raffle = $raffle;
        $this->tickets = $tickets;
        $this->totalPrice = $totalPrice;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
