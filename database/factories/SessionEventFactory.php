<?php

namespace Database\Factories;

use App\Models\SessionEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SessionEvent>
 */
class SessionEventFactory extends Factory
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
            'type' => fake()->randomElement(['participant_joined', 'state_changed', 'hint', 'metrics', 'warning']),
            'title' => fake()->sentence(4),
            'message' => fake()->sentence(10),
            'payload' => ['priority' => fake()->randomElement(['low', 'medium', 'high'])],
            'occurred_at' => now()->subMinutes(random_int(1, 90)),
        ];
    }
}
