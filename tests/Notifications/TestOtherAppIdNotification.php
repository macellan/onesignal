<?php

namespace Macellan\OneSignal\Tests\Notifications;

use Illuminate\Notifications\Notification;
use Macellan\OneSignal\OneSignalMessage;

class TestOtherAppIdNotification extends Notification
{
    public function via()
    {
        return ['onesignal'];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setAppId('otherAppId')
            ->setSubject('Subject')
            ->setBody('Body');
    }
}
