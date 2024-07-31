<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Tests\Fixtures\Notifications;

use Illuminate\Notifications\Notification;
use Macellan\OneSignal\OneSignalMessage;

class TestNotificationByText extends Notification
{
    public function via(): array
    {
        return ['onesignal'];
    }

    public function toOneSignal(): string|OneSignalMessage
    {
        return 'My notification';
    }
}
