<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionActivityLogged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public \App\Models\TrainingSession $session,
        public \App\Models\SessionEvent $eventItem,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('shadow.room.'.$this->session->uuid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'session.activity.logged';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->eventItem->id,
            'type' => $this->eventItem->type,
            'title' => $this->eventItem->title,
            'message' => $this->eventItem->message,
            'payload' => $this->eventItem->payload,
            'occurred_at' => $this->eventItem->occurred_at?->toIso8601String(),
            'user_id' => $this->eventItem->user_id,
        ];
    }
}
