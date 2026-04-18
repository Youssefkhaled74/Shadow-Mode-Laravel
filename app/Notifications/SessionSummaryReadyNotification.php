<?php

namespace App\Notifications;

use App\Models\SessionReport;
use App\Models\TrainingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionSummaryReadyNotification extends Notification
{
    use Queueable;

    public function __construct(
        public TrainingSession $session,
        public SessionReport $report,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Shadow Mode: Session Summary Ready')
            ->line("The summary for '{$this->session->title}' is now available.")
            ->line("Overall score: {$this->report->overall_score}/100")
            ->action('Open Session Report', route('reports.show', $this->session))
            ->line('Keep training. Momentum compounds.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'session_uuid' => $this->session->uuid,
            'report_id' => $this->report->id,
            'overall_score' => $this->report->overall_score,
        ];
    }
}
