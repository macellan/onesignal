<?php

namespace Macellan\OneSignal;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Macellan\OneSignal\Exceptions\CouldNotSendNotification;

class OneSignalChannel
{
    protected string $appId;

    protected int $timeout = 3;

    const ENDPOINT = 'https://onesignal.com/api/v1/notifications';

    public function __construct(string $appId)
    {
        $this->appId = $appId;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return object|null
     * @throws CouldNotSendNotification|\Illuminate\Http\Client\RequestException
     */
    public function send($notifiable, Notification $notification): ?object
    {
        $message = $notification->toOneSignal($notifiable);
        if (is_string($message)) {
            $message = new OneSignalMessage($message);
        }

        if (! $userIds = $notifiable->routeNotificationFor('OneSignal', $notification)) {
            return null;
        }

        $result = Http::timeout($this->timeout)
            ->asJson()->acceptJson()
            ->post(self::ENDPOINT, [
                'app_id' => $this->appId,
                'contents' => $message->getBody(),
                'headings' => $message->getHeadings(),
                'data' => $message->getData(),
                'include_player_ids' => is_array($userIds) ? $userIds : [$userIds],
            ]);

        if ($requestException = $result->toException()) {
            throw $requestException;
        }

        $errors = $result->json('errors');

        if (! empty($errors)) {
            throw CouldNotSendNotification::withErrors($errors);
        }

        return $result;
    }
}
