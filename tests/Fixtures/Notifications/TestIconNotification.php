<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Tests\Fixtures\Notifications;

use Illuminate\Notifications\Notification;
use Macellan\OneSignal\OneSignalMessage;

class TestIconNotification extends Notification
{
    public function via(): array
    {
        return ['onesignal'];
    }

    public function toOneSignal(): OneSignalMessage
    {
        return OneSignalMessage::create()
            ->setSubject('Subject')
            ->setBody('Body')
            ->setIcon('test-icon.jpg');
    }
}
