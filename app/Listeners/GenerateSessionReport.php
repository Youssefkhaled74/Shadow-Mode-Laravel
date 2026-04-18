<?php

namespace App\Listeners;

use App\Events\SessionEnded;
use App\Jobs\BuildSessionReportJob;

class GenerateSessionReport
{
    public function handle(SessionEnded $event): void
    {
        BuildSessionReportJob::dispatch($event->session, $event->actor);
    }
}
