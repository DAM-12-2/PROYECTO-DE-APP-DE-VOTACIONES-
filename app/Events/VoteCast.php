<?php

namespace App\Events;

use App\Models\Vote;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteCast implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $mesaId)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('mesa.' . $this->mesaId)];
    }

    public function broadcastAs(): string
    {
        return 'voto.emitido';
    }

    public function broadcastWith(): array
    {
        return ['mesa_id' => $this->mesaId];
    }
}
