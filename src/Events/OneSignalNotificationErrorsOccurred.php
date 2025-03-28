<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;

class OneSignalNotificationErrorsOccurred
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public mixed $notifiable,
        public Notification $notification,
        public array $errors,
    ) {
        Event::dispatch(new NotificationFailed($notifiable, $notification, 'onesignal', [
            'errors' => $errors,
        ]));
    }
}
