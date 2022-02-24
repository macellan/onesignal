<?php

namespace Macellan\OneSignal;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Macellan\OneSignal\Exceptions\CouldNotSendNotification;

class OneSignalChannel
{
    protected string $appId;

    protected string $endPoint;

    protected int $timeout = 3;

    public function __construct(string $appId)
    {
        $this->appId = $appId;
        $this->endPoint = 'https://onesignal.com/api/v1/notifications';
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return object|null
     * @throws CouldNotSendNotification
     * @noinspection PhpUndefinedMethodInspection
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

        $userIds = is_array($userIds) ? $userIds : [$userIds];

        $result = Http::timeout($this->timeout)
            ->asJson()->acceptJson()
            ->post($this->endPoint, [
                'app_id' => $this->appId,
                'contents' => $message->getBody(),
                'headings' => $message->getHeadings(),
                'data' => $message->getData(),
                'include_player_ids' => $userIds,
            ]);

        $result->toException();

        $errors = $result->json('errors');

        if (! empty($errors)) {
            throw CouldNotSendNotification::withErrors($errors);
        }

        return $result;
    }
}
