<?php

namespace Database\Seeders;

use App\Domain\ShadowMode\Services\SessionReportService;
use App\Models\CoachingHint;
use App\Models\MetricSnapshot;
use App\Models\SessionEvent;
use App\Models\SessionParticipant;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShadowModeDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Shadow Admin',
            'email' => 'admin@shadowmode.test',
        ]);
        $admin->assignRole('admin');

        $coach = User::factory()->create([
            'name' => 'Ava Coach',
            'email' => 'coach@shadowmode.test',
        ]);
        $coach->assignRole('coach');

        $users = User::factory(8)->create();
        $users->each->assignRole('user');

        $sessions = TrainingSession::factory(6)->create([
            'host_id' => $coach->id,
            'coach_id' => $coach->id,
        ]);

        foreach ($sessions as $session) {
            $pickedUsers = $users->random(4);

            foreach ($pickedUsers as $member) {
                SessionParticipant::query()->create([
                    'training_session_id' => $session->id,
                    'user_id' => $member->id,
                    'role' => 'participant',
                    'is_present' => true,
                    'joined_at' => now()->subMinutes(random_int(1, 50)),
                    'last_seen_at' => now()->subMinutes(random_int(0, 3)),
                ]);
            }

            SessionEvent::factory(14)->create([
                'training_session_id' => $session->id,
                'user_id' => $pickedUsers->random()->id,
            ]);

            MetricSnapshot::factory(24)->create([
                'training_session_id' => $session->id,
                'user_id' => $pickedUsers->random()->id,
            ]);

            CoachingHint::factory(10)->create([
                'training_session_id' => $session->id,
                'author_id' => $coach->id,
                'target_user_id' => $pickedUsers->random()->id,
            ]);

            $session->invites()->create([
                'created_by' => $coach->id,
                'token' => Str::random(24),
                'max_uses' => 50,
                'expires_at' => now()->addDays(2),
            ]);
        }

        /** @var SessionReportService $reportService */
        $reportService = app(SessionReportService::class);

        $sessions->each(function (TrainingSession $session) use ($reportService, $coach): void {
            $reportService->generate($session, $coach);
        });
    }
}
