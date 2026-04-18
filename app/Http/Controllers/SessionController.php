<?php

namespace App\Http\Controllers;

use App\Domain\ShadowMode\Services\SessionLifecycleService;
use App\Http\Requests\JoinSessionRequest;
use App\Http\Requests\StoreTrainingSessionRequest;
use App\Http\Requests\UpdateSessionStateRequest;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SessionController extends Controller
{
    public function index(): Response
    {
        $sessions = TrainingSession::query()
            ->with(['host:id,name', 'coach:id,name'])
            ->withCount(['participants', 'events'])
            ->latest()
            ->paginate(12)
            ->through(fn (TrainingSession $session) => [
                'id' => $session->id,
                'uuid' => $session->uuid,
                'room_code' => $session->room_code,
                'title' => $session->title,
                'scenario_type' => $session->scenario_type,
                'state' => $session->state,
                'scheduled_for' => $session->scheduled_for?->toIso8601String(),
                'average_score' => $session->average_score,
                'host' => $session->host,
                'coach' => $session->coach,
                'participants_count' => $session->participants_count,
                'events_count' => $session->events_count,
            ]);

        return Inertia::render('Sessions/Index', [
            'sessions' => $sessions,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', TrainingSession::class);

        return Inertia::render('Sessions/Create');
    }

    public function store(StoreTrainingSessionRequest $request, SessionLifecycleService $sessionService): RedirectResponse
    {
        $session = $sessionService->createSession($request->user(), $request->validated());

        return to_route('rooms.show', $session)->with('success', 'Session created successfully.');
    }

    public function show(TrainingSession $trainingSession): Response
    {
        $this->authorize('view', $trainingSession);

        $trainingSession->load([
            'host:id,name',
            'coach:id,name',
            'participants.user:id,name,email',
            'events' => fn ($query) => $query->latest('occurred_at')->limit(30),
            'hints' => fn ($query) => $query->latest('sent_at')->limit(20),
            'metrics' => fn ($query) => $query->latest('recorded_at')->limit(50),
            'invites',
            'report',
        ]);

        return Inertia::render('Sessions/Show', [
            'session' => $trainingSession,
        ]);
    }

    public function joinForm(): Response
    {
        return Inertia::render('Sessions/Join');
    }

    public function join(JoinSessionRequest $request, SessionLifecycleService $sessionService): RedirectResponse
    {
        $data = $request->validated();

        $session = TrainingSession::query()
            ->where('room_code', $data['room_code'])
            ->firstOrFail();

        $sessionService->joinSession($session, $request->user());

        return to_route('rooms.show', $session)->with('success', 'You joined the session.');
    }

    public function updateState(
        UpdateSessionStateRequest $request,
        TrainingSession $trainingSession,
        SessionLifecycleService $sessionService
    ): RedirectResponse {
        $sessionService->transitionState($trainingSession, $request->validated('state'), $request->user());

        return back()->with('success', 'Session state updated.');
    }

    public function leave(TrainingSession $trainingSession, SessionLifecycleService $sessionService): RedirectResponse
    {
        $sessionService->leaveSession($trainingSession, auth()->user());

        return to_route('sessions.index')->with('success', 'You left the room.');
    }
}
