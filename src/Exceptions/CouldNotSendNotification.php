<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function withErrors(string $response): CouldNotSendNotification
    {
        return new self('OneSignal responded with an error: `'.$response.'`');
    }
}
