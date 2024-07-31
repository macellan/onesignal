<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Tests\Fixtures\Notifications;

use Illuminate\Notifications\Notification;
use Macellan\OneSignal\OneSignalMessage;

class TestOtherAppIdNotification extends Notification
{
    public function via(): array
    {
        return ['onesignal'];
    }

    public function toOneSignal(): OneSignalMessage
    {
        return OneSignalMessage::create()
            ->setAppId('other_app_id')
            ->setSubject('Subject')
            ->setBody('Body');
    }
}
