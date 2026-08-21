<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $student;

    public function __construct($student)
    {
        $this->student = $student;
    }

    public function broadcastOn()
    {
        return new Channel('students-channel');
    }

    public function broadcastAs()
    {
        return 'student.updated';
    }
}