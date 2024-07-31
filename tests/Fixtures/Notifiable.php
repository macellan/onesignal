<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Tests\Fixtures;

class Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForOneSignal(): string
    {
        return 'player_id';
    }
}
