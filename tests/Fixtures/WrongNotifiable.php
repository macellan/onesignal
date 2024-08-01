<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Tests\Fixtures;

class WrongNotifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForOneSignal(): ?string
    {
        return null;
    }
}
