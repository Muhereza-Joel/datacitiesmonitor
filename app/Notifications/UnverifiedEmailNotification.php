<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnverifiedEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Please Confirm Your Email Address')
            ->line('It looks like you haven\'t confirmed your email address yet.')
            ->action('Confirm Email', url('/email/verify'))
            ->line('Thank you for using M $ E Monitor!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Please confirm your email address.',
            'notification_type' => 'warning',
            'notification_title' => 'Security Alert',
            'request_verification_url' => route('verification.request', $notifiable->id),
        ];
    }
}
