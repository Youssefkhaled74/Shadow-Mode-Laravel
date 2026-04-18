<?php

namespace Database\Factories;

use App\Models\TrainingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TrainingSession>
 */
class TrainingSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'room_code' => strtoupper(fake()->bothify('??##??##')),
            'title' => fake()->randomElement([
                'Mock Product Manager Interview',
                'Enterprise Sales Discovery Drill',
                'Salary Negotiation Challenge',
                'Executive Communication Simulation',
            ]),
            'scenario_type' => fake()->randomElement(['interview', 'sales', 'negotiation', 'communication']),
            'description' => fake()->paragraph(),
            'host_id' => \App\Models\User::factory(),
            'coach_id' => \App\Models\User::factory(),
            'state' => fake()->randomElement(['waiting', 'live', 'paused', 'ended']),
            'scheduled_for' => now()->addHours(random_int(1, 72)),
            'started_at' => fake()->boolean(60) ? now()->subMinutes(random_int(5, 90)) : null,
            'ended_at' => fake()->boolean(25) ? now()->subMinutes(random_int(1, 40)) : null,
            'settings' => [
                'liveHints' => true,
                'autoScoring' => true,
                'presenceTracking' => true,
            ],
            'average_score' => random_int(52, 94),
        ];
    }
}
