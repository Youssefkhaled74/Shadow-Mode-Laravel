<?php

namespace Database\Factories;

use App\Models\MetricSnapshot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MetricSnapshot>
 */
class MetricSnapshotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'training_session_id' => \App\Models\TrainingSession::factory(),
            'user_id' => \App\Models\User::factory(),
            'confidence_score' => random_int(45, 95),
            'clarity_score' => random_int(40, 95),
            'pace_score' => random_int(40, 95),
            'overall_score' => random_int(45, 95),
            'filler_word_count' => random_int(0, 8),
            'missed_question_count' => random_int(0, 3),
            'meta' => [
                'speaking_rate_wpm' => random_int(105, 175),
            ],
            'recorded_at' => now()->subMinutes(random_int(1, 90)),
        ];
    }
}
