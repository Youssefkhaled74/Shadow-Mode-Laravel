<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManageUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserManagementController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Users', [
            'users' => User::query()
                ->with('roles:name')
                ->latest()
                ->paginate(15),
        ]);
    }

    public function update(ManageUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'headline' => $validated['headline'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'timezone' => $validated['timezone'] ?? 'UTC',
        ]);

        $user->syncRoles([$validated['role']]);

        return back()->with('success', 'User updated.');
    }
}
