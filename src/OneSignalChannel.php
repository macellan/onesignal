<?php

declare(strict_types=1);

namespace Macellan\OneSignal;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Macellan\OneSignal\Events\OneSignalNotificationErrorsOccurred;
use Macellan\OneSignal\Events\OneSignalNotificationNotSent;
use Macellan\OneSignal\Events\OneSignalNotificationSending;
use Macellan\OneSignal\Events\OneSignalNotificationSent;
use Macellan\OneSignal\Exceptions\CouldNotSendNotification;

class OneSignalChannel
{
    protected string $appId;

    protected int $timeout = 0;

    const ENDPOINT = 'https://onesignal.com/api/v1/notifications';

    public function __construct(string $appId)
    {
        $this->appId = $appId;
    }

    /**
     * Send the given notification.
     *
     *
     * @throws CouldNotSendNotification|\Illuminate\Http\Client\RequestException|\Illuminate\Http\Client\ConnectionException
     */
    public function send(mixed $notifiable, Notification $notification): void
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $message = $notification->toOneSignal($notifiable);

        if (is_string($message)) {
            $message = new OneSignalMessage($message);
        }

        if (! $userIds = $notifiable->routeNotificationFor('OneSignal', $notification)) {
            OneSignalNotificationNotSent::dispatch($notifiable, $notification);

            return;
        }

        OneSignalNotificationSending::dispatch($notifiable, $notification);

        $result = Http::timeout($this->timeout)
            ->asJson()->acceptJson()
            ->post(self::ENDPOINT, [
                'app_id' => $message->getAppId() ?? $this->appId,
                'headings' => $message->getHeadings(),
                'contents' => $message->getBody(),
                'data' => $message->getData(),
                'web_url' => $message->getWebUrl(),
                'large_icon' => $message->getIcon(),
                'huawei_large_icon' => $message->getIcon(),
                'ios_attachments' => ['icon' => $message->getIcon()],
                'include_player_ids' => is_array($userIds) ? $userIds : [$userIds],

            ]);

        OneSignalNotificationSent::dispatch($notifiable, $notification, $result->json());

        if ($requestException = $result->toException()) {
            throw $requestException;
        }

        $errors = $result->json('errors');

        if (! empty($errors)) {
            OneSignalNotificationErrorsOccurred::dispatch($notifiable, $notification, $errors);

            throw CouldNotSendNotification::withErrors($result->body());
        }
    }
}
