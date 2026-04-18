<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use Inertia\Inertia;
use Inertia\Response;

class SessionRoomController extends Controller
{
    public function show(TrainingSession $trainingSession): Response
    {
        $this->authorize('view', $trainingSession);

        $trainingSession->load([
            'host:id,name',
            'coach:id,name',
            'participants.user:id,name,email',
            'events' => fn ($query) => $query->latest('occurred_at')->limit(20),
            'hints' => fn ($query) => $query->latest('sent_at')->limit(20),
            'metrics' => fn ($query) => $query->latest('recorded_at')->limit(50),
        ]);

        return Inertia::render('Rooms/LiveRoom', [
            'session' => $trainingSession,
        ]);
    }
}
