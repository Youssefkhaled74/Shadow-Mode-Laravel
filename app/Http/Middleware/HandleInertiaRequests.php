<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user()
                    ? [
                        ...$request->user()->only([
                            'id',
                            'name',
                            'email',
                            'avatar_url',
                            'headline',
                            'timezone',
                            'preferences',
                        ]),
                        'roles' => $request->user()->getRoleNames()->values(),
                    ]
                    : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'warning' => fn () => $request->session()->get('warning'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'reverb' => [
                'key' => env('VITE_REVERB_APP_KEY'),
                'host' => env('VITE_REVERB_HOST'),
                'port' => env('VITE_REVERB_PORT'),
                'scheme' => env('VITE_REVERB_SCHEME', 'http'),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}
