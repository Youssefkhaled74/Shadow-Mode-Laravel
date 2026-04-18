<?php

namespace App\Domain\ShadowMode\Services;

use App\Events\SessionActivityLogged;
use App\Events\SessionEnded;
use App\Events\SessionStateUpdated;
use App\Models\SessionEvent;
use App\Models\SessionInvite;
use App\Models\SessionParticipant;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SessionLifecycleService
{
    public function createSession(User $host, array $data): TrainingSession
    {
        return DB::transaction(function () use ($host, $data): TrainingSession {
            $session = TrainingSession::query()->create([
                ...$data,
                'host_id' => $host->id,
                'room_code' => strtoupper(Str::random(8)),
                'uuid' => (string) Str::uuid(),
                'settings' => $data['settings'] ?? [
                    'liveHints' => true,
                    'autoScoring' => true,
                    'presenceTracking' => true,
                ],
            ]);

            SessionParticipant::query()->create([
                'training_session_id' => $session->id,
                'user_id' => $host->id,
                'role' => 'coach',
                'is_present' => true,
                'joined_at' => now(),
                'last_seen_at' => now(),
            ]);

            SessionInvite::query()->create([
                'training_session_id' => $session->id,
                'created_by' => $host->id,
                'token' => Str::random(24),
                'max_uses' => 25,
                'expires_at' => now()->addDay(),
            ]);

            $this->logActivity($session, 'session_created', 'Room created', "Room {$session->room_code} has been provisioned.", $host);

            return $session;
        });
    }

    public function joinSession(TrainingSession $session, User $user, string $role = 'participant'): SessionParticipant
    {
        $participant = SessionParticipant::query()->updateOrCreate(
            [
                'training_session_id' => $session->id,
                'user_id' => $user->id,
            ],
            [
                'role' => $role,
                'is_present' => true,
                'joined_at' => now(),
                'left_at' => null,
                'last_seen_at' => now(),
            ],
        );

        $this->logActivity($session, 'participant_joined', "{$user->name} joined", null, $user, ['role' => $role]);

        return $participant;
    }

    public function leaveSession(TrainingSession $session, User $user): void
    {
        SessionParticipant::query()
            ->where('training_session_id', $session->id)
            ->where('user_id', $user->id)
            ->update([
                'is_present' => false,
                'left_at' => now(),
                'last_seen_at' => now(),
            ]);

        $this->logActivity($session, 'participant_left', "{$user->name} left", null, $user);
    }

    public function transitionState(TrainingSession $session, string $state, User $actor): TrainingSession
    {
        $timestamps = [];

        if ($state === 'live' && $session->started_at === null) {
            $timestamps['started_at'] = now();
        }

        if ($state === 'ended') {
            $timestamps['ended_at'] = now();
        }

        $session->forceFill([
            'state' => $state,
            ...$timestamps,
        ])->save();

        $this->logActivity(
            $session,
            'state_changed',
            "Session moved to {$state}",
            "Changed by {$actor->name}.",
            $actor,
            ['state' => $state]
        );

        event(new SessionStateUpdated($session, $actor));

        if ($state === 'ended') {
            event(new SessionEnded($session, $actor));
        }

        return $session->fresh();
    }

    public function logActivity(
        TrainingSession $session,
        string $type,
        string $title,
        ?string $message = null,
        ?User $actor = null,
        array $payload = [],
        ?Carbon $occurredAt = null
    ): SessionEvent {
        $event = SessionEvent::query()->create([
            'training_session_id' => $session->id,
            'user_id' => $actor?->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'payload' => $payload,
            'occurred_at' => $occurredAt ?? now(),
        ]);

        event(new SessionActivityLogged($session, $event));

        return $event;
    }
}
