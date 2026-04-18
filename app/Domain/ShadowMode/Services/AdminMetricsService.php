<?php

namespace App\Domain\ShadowMode\Services;

use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminMetricsService
{
    public function summary(): array
    {
        return [
            'users' => User::query()->count(),
            'coaches' => User::role('coach')->count(),
            'active_rooms' => TrainingSession::query()->where('state', 'live')->count(),
            'sessions_this_week' => TrainingSession::query()->where('created_at', '>=', now()->startOfWeek())->count(),
            'avg_platform_score' => (float) round(TrainingSession::query()->avg('average_score') ?? 0, 1),
            'state_distribution' => TrainingSession::query()
                ->select('state', DB::raw('count(*) as total'))
                ->groupBy('state')
                ->pluck('total', 'state'),
        ];
    }
}
