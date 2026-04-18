<?php

namespace App\Http\Controllers;

use App\Domain\ShadowMode\Services\CoachingStreamService;
use App\Domain\ShadowMode\Services\SessionLifecycleService;
use App\Http\Requests\RecordMetricSnapshotRequest;
use App\Http\Requests\SendCoachingHintRequest;
use App\Models\TrainingSession;
use Illuminate\Http\JsonResponse;

class LiveCoachingController extends Controller
{
    public function storeHint(
        SendCoachingHintRequest $request,
        TrainingSession $trainingSession,
        CoachingStreamService $coachingService,
        SessionLifecycleService $sessionLifecycleService
    ): JsonResponse {
        $this->authorize('view', $trainingSession);

        $hint = $coachingService->publishHint($trainingSession, $request->user(), $request->validated());

        $sessionLifecycleService->logActivity(
            $trainingSession,
            'hint',
            'Live coaching hint published',
            $hint->content,
            $request->user(),
            ['severity' => $hint->severity, 'category' => $hint->category]
        );

        return response()->json(['hint' => $hint], 201);
    }

    public function storeMetrics(
        RecordMetricSnapshotRequest $request,
        TrainingSession $trainingSession,
        CoachingStreamService $coachingService,
        SessionLifecycleService $sessionLifecycleService
    ): JsonResponse {
        $this->authorize('view', $trainingSession);

        $snapshot = $coachingService->recordMetrics($trainingSession, $request->user(), $request->validated());

        $sessionLifecycleService->logActivity(
            $trainingSession,
            'metrics',
            'Live score pulse',
            "Overall score updated to {$snapshot->overall_score}.",
            $request->user(),
            ['overall' => $snapshot->overall_score]
        );

        return response()->json(['snapshot' => $snapshot], 201);
    }
}
