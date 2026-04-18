<?php

namespace App\Domain\ShadowMode\Services;

use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function userSummary(User $user): array
    {
        $baseQuery = TrainingSession::query()
            ->where(function ($query) use ($user): void {
                $query->where('host_id', $user->id)
                    ->orWhere('coach_id', $user->id)
                    ->orWhereHas('participants', fn ($participantQuery) => $participantQuery->where('user_id', $user->id));
            });

        $totalSessions = (clone $baseQuery)->count();
        $avgScore = (float) round((clone $baseQuery)->avg('average_score') ?? 0, 1);

        $trend = TrainingSession::query()
            ->selectRaw('DATE(created_at) as day, AVG(average_score) as score')
            ->where('host_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $upcoming = TrainingSession::query()
            ->with(['host:id,name', 'coach:id,name'])
            ->where('scheduled_for', '>=', now())
            ->where('state', '!=', 'ended')
            ->orderBy('scheduled_for')
            ->limit(5)
            ->get();

        $recentActivity = DB::table('session_events')
            ->join('training_sessions', 'training_sessions.id', '=', 'session_events.training_session_id')
            ->select('session_events.*', 'training_sessions.title as session_title')
            ->where('training_sessions.host_id', $user->id)
            ->orderByDesc('occurred_at')
            ->limit(8)
            ->get();

        return [
            'cards' => [
                'total_sessions' => $totalSessions,
                'average_score' => $avgScore,
                'live_rooms' => (clone $baseQuery)->where('state', 'live')->count(),
                'reports_generated' => (clone $baseQuery)->whereHas('report')->count(),
            ],
            'trend' => $trend,
            'upcoming' => $upcoming,
            'recent_activity' => $recentActivity,
        ];
    }
}
