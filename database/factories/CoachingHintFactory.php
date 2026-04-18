<?php

namespace Database\Factories;

use App\Models\CoachingHint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CoachingHint>
 */
class CoachingHintFactory extends Factory
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
            'author_id' => \App\Models\User::factory(),
            'target_user_id' => \App\Models\User::factory(),
            'category' => fake()->randomElement(['confidence', 'clarity', 'pace', 'filler', 'question', 'follow_up', 'general']),
            'severity' => fake()->randomElement(['low', 'medium', 'high']),
            'is_system' => fake()->boolean(15),
            'content' => fake()->randomElement([
                'Pause one second before your main point.',
                'Answer the exact question before expanding.',
                'Use one concrete metric in your next response.',
                'Strong confidence, now tighten the structure.',
            ]),
            'meta' => ['source' => fake()->randomElement(['coach', 'system'])],
            'sent_at' => now()->subMinutes(random_int(1, 90)),
        ];
    }
}
