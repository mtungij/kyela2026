<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;

class AccountClosed extends Notification
{
    use Queueable;

    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    // Single via method
    public function via($notifiable)
    {
        return ['webpush'];
    }


public function toWebPush($notifiable, $notification)
{
    return (new WebPushMessage)
        ->title('Kalumbulu Group')
        ->body($this->message)
        ->icon('/icon.png')
        ->sound('/notification.mp3')
        ->action('Open Dashboard', 'open_dashboard');
}
}