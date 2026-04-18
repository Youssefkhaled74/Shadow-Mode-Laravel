<?php

namespace App\Policies;

use App\Models\SessionReport;
use App\Models\User;

class SessionReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['user', 'coach', 'admin']);
    }

    public function view(User $user, SessionReport $sessionReport): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $sessionReport->session->host_id === $user->id
            || $sessionReport->session->coach_id === $user->id
            || $sessionReport->session->participants()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['user', 'coach', 'admin']);
    }

    public function update(User $user, SessionReport $sessionReport): bool
    {
        return $user->hasRole('admin') || $sessionReport->session->host_id === $user->id;
    }

    public function delete(User $user, SessionReport $sessionReport): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, SessionReport $sessionReport): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, SessionReport $sessionReport): bool
    {
        return $user->hasRole('admin');
    }
}
