<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class OneSignalNotificationNotSent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public mixed $notifiable,
        public Notification $notification,
    ) {
        // .
    }
}
