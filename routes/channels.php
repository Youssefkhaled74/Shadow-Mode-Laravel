<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\TrainingSession;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('shadow.room.{sessionUuid}', function ($user, string $sessionUuid) {
    $session = TrainingSession::query()->where('uuid', $sessionUuid)->first();

    if (! $session) {
        return false;
    }

    $isParticipant = $session->participants()->where('user_id', $user->id)->exists();

    if ($session->host_id !== $user->id && $session->coach_id !== $user->id && ! $isParticipant && ! $user->hasRole('admin')) {
        return false;
    }

    return [
        'id' => $user->id,
        'name' => $user->name,
        'role' => $user->getRoleNames()->first() ?? 'user',
    ];
});
