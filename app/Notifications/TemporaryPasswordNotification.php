<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Env;

class TemporaryPasswordNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    protected string $email;
    protected string $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email, $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        dd([
            'email' => $this->email,
            'token' => $this->token,
        ]);
        $url = Env::get('APP_URL') . '/reset-password/' . $this->passwordResetToken->token;
        $firstName = $notifiable->first_name;
        return (new MailMessage())
            ->subject('Temporary Password Notification')
            ->greeting("Hello, $firstName!")
            ->line('You are receiving this email because we received a password reset request for your account or this is a new account.')
            ->action('Reset Password', $url);
    }
}
