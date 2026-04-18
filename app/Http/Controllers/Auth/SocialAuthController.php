<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class SocialAuthController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(config('services.socialite.enabled', false), 404);

        return back()->with('warning', "Social login provider '{$provider}' is scaffolded but not connected yet.");
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(config('services.socialite.enabled', false), 404);

        return to_route('login')->with('warning', "Callback for '{$provider}' received. Connect Socialite credentials to activate.");
    }
}
