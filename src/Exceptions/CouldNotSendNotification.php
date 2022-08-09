<?php

namespace Macellan\OneSignal\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function withErrors(string $response): CouldNotSendNotification
    {
        return new static('OneSignal responded with an error: `'.$response.'`');
    }
}
