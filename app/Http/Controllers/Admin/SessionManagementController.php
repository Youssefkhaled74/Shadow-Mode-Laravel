<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SessionManagementController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Sessions', [
            'sessions' => TrainingSession::query()
                ->with(['host:id,name', 'coach:id,name'])
                ->withCount(['participants', 'events'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function destroy(TrainingSession $trainingSession): RedirectResponse
    {
        $trainingSession->delete();

        return back()->with('success', 'Session archived.');
    }
}
