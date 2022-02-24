<?php

namespace Macellan\OneSignal\Tests;

use Illuminate\Notifications\Notification;
use Macellan\OneSignal\OneSignalMessage;

class TestNotification extends Notification
{
    public function via()
    {
        return ['onesignal'];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject('Subject')
            ->setBody('Body');
    }
}
