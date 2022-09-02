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

    public function toOneSignal()
    {
        return OneSignalMessage::create()
            ->setAppId('other_app_id')
            ->setSubject('Subject')
            ->setBody('Body');
    }
}
