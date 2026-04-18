<?php

namespace App\Policies;

use App\Models\TrainingSession;
use App\Models\User;

class TrainingSessionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['user', 'coach', 'admin']);
    }

    public function view(User $user, TrainingSession $trainingSession): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $trainingSession->host_id === $user->id
            || $trainingSession->coach_id === $user->id
            || $trainingSession->participants()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['user', 'coach', 'admin']);
    }

    public function update(User $user, TrainingSession $trainingSession): bool
    {
        return $user->hasRole('admin') || $trainingSession->host_id === $user->id;
    }

    public function delete(User $user, TrainingSession $trainingSession): bool
    {
        return $user->hasRole('admin') || $trainingSession->host_id === $user->id;
    }

    public function restore(User $user, TrainingSession $trainingSession): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, TrainingSession $trainingSession): bool
    {
        return $user->hasRole('admin');
    }
}
