<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionStateUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public \App\Models\TrainingSession $session,
        public \App\Models\User $actor,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('shadow.room.'.$this->session->uuid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'session.state.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'session_uuid' => $this->session->uuid,
            'state' => $this->session->state,
            'started_at' => $this->session->started_at?->toIso8601String(),
            'ended_at' => $this->session->ended_at?->toIso8601String(),
            'actor' => [
                'id' => $this->actor->id,
                'name' => $this->actor->name,
            ],
        ];
    }
}
