<?php

namespace App\Http\Controllers\Admin;

use App\Domain\ShadowMode\Services\AdminMetricsService;
use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function __invoke(AdminMetricsService $metricsService): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'metrics' => $metricsService->summary(),
            'recentSessions' => TrainingSession::query()
                ->with(['host:id,name', 'coach:id,name'])
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
