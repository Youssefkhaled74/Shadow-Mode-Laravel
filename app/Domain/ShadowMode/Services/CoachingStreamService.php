<?php

namespace App\Domain\ShadowMode\Services;

use App\Events\CoachingHintPublished;
use App\Events\MetricsUpdated;
use App\Models\CoachingHint;
use App\Models\MetricSnapshot;
use App\Models\TrainingSession;
use App\Models\User;

class CoachingStreamService
{
    public function publishHint(TrainingSession $session, User $author, array $data): CoachingHint
    {
        $hint = CoachingHint::query()->create([
            'training_session_id' => $session->id,
            'author_id' => $author->id,
            'target_user_id' => $data['target_user_id'] ?? null,
            'category' => $data['category'],
            'severity' => $data['severity'],
            'content' => $data['content'],
            'is_system' => false,
            'meta' => $data['meta'] ?? null,
            'sent_at' => now(),
        ]);

        event(new CoachingHintPublished($session, $hint));

        return $hint;
    }

    public function recordMetrics(TrainingSession $session, User $actor, array $payload): MetricSnapshot
    {
        $snapshot = MetricSnapshot::query()->create([
            'training_session_id' => $session->id,
            'user_id' => $payload['user_id'] ?? $actor->id,
            'confidence_score' => $payload['confidence_score'],
            'clarity_score' => $payload['clarity_score'],
            'pace_score' => $payload['pace_score'],
            'overall_score' => $payload['overall_score'],
            'filler_word_count' => $payload['filler_word_count'],
            'missed_question_count' => $payload['missed_question_count'],
            'meta' => $payload['meta'] ?? null,
            'recorded_at' => now(),
        ]);

        $session->update([
            'average_score' => round((float) $session->metrics()->avg('overall_score'), 2),
        ]);

        event(new MetricsUpdated($session, $snapshot));

        return $snapshot;
    }
}
