<?php

namespace Macellan\OneSignal\Tests\Notifications;

use Illuminate\Notifications\Notification;
use Macellan\OneSignal\OneSignalMessage;

class TestAppIdNotification extends Notification
{
    public function via()
    {
        return ['onesignal'];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setAppId('different_app_id')
            ->setSubject('Subject')
            ->setBody('Body');
    }
}
