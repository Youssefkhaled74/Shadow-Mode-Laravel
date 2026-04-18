<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MetricsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public \App\Models\TrainingSession $session,
        public \App\Models\MetricSnapshot $snapshot,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('shadow.room.'.$this->session->uuid),
        ];
    }

    public function broadcastAs(): string
    {
        return 'metrics.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'snapshot' => [
                'confidence_score' => $this->snapshot->confidence_score,
                'clarity_score' => $this->snapshot->clarity_score,
                'pace_score' => $this->snapshot->pace_score,
                'overall_score' => $this->snapshot->overall_score,
                'filler_word_count' => $this->snapshot->filler_word_count,
                'missed_question_count' => $this->snapshot->missed_question_count,
                'recorded_at' => $this->snapshot->recorded_at?->toIso8601String(),
            ],
            'session_average_score' => $this->session->average_score,
        ];
    }
}
