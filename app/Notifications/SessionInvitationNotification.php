<?php

namespace App\Notifications;

use App\Models\SessionInvite;
use App\Models\TrainingSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionInvitationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public TrainingSession $session,
        public SessionInvite $invite,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You are invited to a Shadow Mode live session')
            ->line("Session: {$this->session->title}")
            ->line("Room code: {$this->session->room_code}")
            ->action('Join Session', route('sessions.join', ['invite' => $this->invite->token]))
            ->line('This invite expires soon. See you in the room.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'session_uuid' => $this->session->uuid,
            'invite_token' => $this->invite->token,
        ];
    }
}
