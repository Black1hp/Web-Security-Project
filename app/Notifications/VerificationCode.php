<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class VerificationCode extends Notification
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public \DateTime $expiresAt
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Email Verification Code')
            ->line('Please use the following code to verify your email address:')
            ->line($this->code)
            ->line('This code will expire in 10 minutes.')
            ->line('If you did not request this verification, please ignore this email.');
    }
}
