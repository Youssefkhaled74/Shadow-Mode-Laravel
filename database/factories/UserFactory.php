<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'avatar_url' => 'https://api.dicebear.com/9.x/lorelei/svg?seed='.fake()->uuid(),
            'timezone' => fake()->randomElement(['UTC', 'America/New_York', 'Europe/London', 'Africa/Cairo']),
            'headline' => fake()->randomElement(['Interview Candidate', 'Sales Lead', 'Coach', 'Communication Specialist']),
            'bio' => fake()->sentence(14),
            'preferences' => [
                'theme' => fake()->randomElement(['light', 'dark']),
                'notifications' => true,
            ],
            'last_active_at' => now()->subMinutes(random_int(1, 500)),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
