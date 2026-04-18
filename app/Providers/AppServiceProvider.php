<?php

namespace App\Providers;

use App\Events\SessionEnded;
use App\Listeners\GenerateSessionReport;
use App\Models\SessionReport;
use App\Models\TrainingSession;
use App\Policies\SessionReportPolicy;
use App\Policies\TrainingSessionPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(TrainingSession::class, TrainingSessionPolicy::class);
        Gate::policy(SessionReport::class, SessionReportPolicy::class);

        Event::listen(SessionEnded::class, GenerateSessionReport::class);

        Vite::prefetch(concurrency: 3);
    }
}
