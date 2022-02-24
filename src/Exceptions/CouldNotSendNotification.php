<?php

namespace Macellan\OneSignal\Exceptions;

class CouldNotSendNotification extends \Exception
{
    public static function withErrors(array $errors): CouldNotSendNotification
    {
        return new static('OneSignal responded with an error: `'.implode(',', $errors).'`');
    }
}