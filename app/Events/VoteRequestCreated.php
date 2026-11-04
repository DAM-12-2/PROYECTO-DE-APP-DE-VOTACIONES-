<?php

namespace App\Events;

use App\Models\VoteRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteRequestCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public VoteRequest $solicitud)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('mesa.' . $this->solicitud->mesa_id)];
    }

    public function broadcastAs(): string
    {
        return 'solicitud.creada';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->solicitud->id,
            'estado' => $this->solicitud->estado,
            'estudiante' => trim(
                ($this->solicitud->student?->nombre ?? '') . ' ' . ($this->solicitud->student?->apellidos ?? '')
            ),
            'identificacion' => $this->solicitud->student?->identificacion,
        ];
    }
}
