<?php

namespace App\Jobs;

use App\Domain\ShadowMode\Services\SessionReportService;
use App\Models\TrainingSession;
use App\Models\User;
use App\Notifications\SessionSummaryReadyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class BuildSessionReportJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TrainingSession $session,
        public ?User $actor = null,
    ) {}

    public function handle(SessionReportService $reportService): void
    {
        $report = $reportService->generate($this->session->fresh(), $this->actor);

        $recipients = collect([$this->session->host, $this->session->coach])
            ->filter()
            ->unique('id');

        foreach ($recipients as $recipient) {
            $recipient->notify(new SessionSummaryReadyNotification($this->session, $report));
        }
    }
}
