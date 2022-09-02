<?php

namespace Macellan\OneSignal\Tests\Notifications;

use Illuminate\Notifications\Notification;
use Macellan\OneSignal\OneSignalMessage;

class TestIconNotification extends Notification
{
    public function via()
    {
        return ['onesignal'];
    }

    public function toOneSignal()
    {
        return OneSignalMessage::create()
            ->setSubject('Subject')
            ->setBody('Body')
            ->setIcon('test-icon.jpg');
    }
}
