<?php

namespace Macellan\OneSignal\Tests;

class Notifiable
{
    use \Illuminate\Notifications\Notifiable;

    public function routeNotificationForOneSignal(): string
    {
        return 'player_id';
    }
}
