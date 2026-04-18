<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CoachingHintPublished implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public \App\Models\TrainingSession $session,
        public \App\Models\CoachingHint $hint,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('shadow.room.'.$this->session->uuid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'coaching.hint.published';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->hint->id,
            'category' => $this->hint->category,
            'severity' => $this->hint->severity,
            'content' => $this->hint->content,
            'target_user_id' => $this->hint->target_user_id,
            'sent_at' => $this->hint->sent_at?->toIso8601String(),
            'author' => [
                'id' => $this->hint->author?->id,
                'name' => $this->hint->author?->name,
            ],
        ];
    }
}
