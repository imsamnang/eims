<?php
namespace App\Notifications;

use Spatie\Backup\Notifications\Notifications\BackupHasFailed as BaseNotification;
use NotificationChannels\PusherPushNotifications\Message;

class BackupHasFailed extends BaseNotification
{
    public function toPushNotification($notifiable)
    {
        return Message::create()
            ->iOS()
            ->badge(1)
            ->sound('fail')
            ->body("The backup of {$this->getApplicationName()} to disk {$this->getDiskName()} has failed");
    }
}
