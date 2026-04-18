<?php

namespace App\Http\Controllers;

use App\Domain\ShadowMode\Services\SessionReportService;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SessionReportController extends Controller
{
    public function show(TrainingSession $trainingSession): Response|RedirectResponse
    {
        $report = $trainingSession->report()->with('timelineMoments')->first();

        if (! $report) {
            return to_route('rooms.show', $trainingSession)
                ->with('warning', 'Report is not generated yet. Generate it first.');
        }

        $this->authorize('view', $report);

        return Inertia::render('Reports/Show', [
            'session' => $trainingSession->load('host:id,name', 'coach:id,name'),
            'report' => $report,
        ]);
    }

    public function generate(TrainingSession $trainingSession, SessionReportService $reportService): RedirectResponse
    {
        $this->authorize('create', \App\Models\SessionReport::class);
        $this->authorize('view', $trainingSession);

        $reportService->generate($trainingSession, auth()->user());

        return to_route('reports.show', $trainingSession)->with('success', 'Report generated.');
    }
}
