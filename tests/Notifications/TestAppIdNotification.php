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
            ->setSubject('Subject')
            ->setBody('Body');
    }

    public function toOneSignalAppId()
    {
        return 'test_change_app_id';
    }
}
