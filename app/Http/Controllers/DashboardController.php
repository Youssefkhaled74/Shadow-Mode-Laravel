<?php

namespace App\Http\Controllers;

use App\Domain\ShadowMode\Services\DashboardService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(DashboardService $dashboardService): Response
    {
        return Inertia::render('Dashboard', [
            'summary' => $dashboardService->userSummary(auth()->user()),
        ]);
    }
}
