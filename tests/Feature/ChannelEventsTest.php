<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Macellan\OneSignal\Events\OneSignalNotificationErrorsOccurred;
use Macellan\OneSignal\Events\OneSignalNotificationNotSent;
use Macellan\OneSignal\Events\OneSignalNotificationSending;
use Macellan\OneSignal\Events\OneSignalNotificationSent;
use Macellan\OneSignal\Exceptions\CouldNotSendNotification;
use Macellan\OneSignal\OneSignalChannel;
use Macellan\OneSignal\Tests\Fixtures\Notifiable;
use Macellan\OneSignal\Tests\Fixtures\Notifications\TestNotification;
use Macellan\OneSignal\Tests\Fixtures\Notifications\TestNotificationByText;
use Macellan\OneSignal\Tests\Fixtures\WrongNotifiable;

test('notification not sent', function () {
    Event::fake();

    Http::fake([
        'api/v1/notifications' => Http::response(),
    ]);

    (new WrongNotifiable)->notify(new TestNotificationByText);

    Http::assertNothingSent();

    Event::assertDispatched(OneSignalNotificationNotSent::class);
});

test('notification sent', function () {
    Event::fake();

    Http::fake([
        'api/v1/notifications' => Http::response([
            'id' => '931082f5-e442-42b1-a951-19e7e45dee39',
            'recipients' => 1,
            'external_id' => null,
        ]),
    ]);

    (new Notifiable)->notify(new TestNotification);

    Http::assertSent(function (Request $request) {
        return $request->url() === OneSignalChannel::ENDPOINT &&
            $request['app_id'] === $this->appId &&
            $request['include_player_ids'] === ['player_id'] &&
            $request['headings'] === ['en' => 'Subject'] &&
            $request['contents'] === ['en' => 'Body'] &&
            $request['data'] === null;
    });

    Event::assertNotDispatched(OneSignalNotificationNotSent::class);

    Event::assertDispatched(OneSignalNotificationSending::class, static function ($event) {
        return $event->notification instanceof TestNotification;
    });

    Event::assertDispatched(OneSignalNotificationSent::class, static function ($event) {
        return $event->response['id'] === '931082f5-e442-42b1-a951-19e7e45dee39';
    });

    Event::assertNotDispatched(OneSignalNotificationErrorsOccurred::class);
});

test('notification errors occurred', function () {
    Event::fake();

    Http::fake([
        'api/v1/notifications' => Http::response([
            'id' => '',
            'recipients' => 0,
            'errors' => [
                'All included players are not subscribed',
            ],
        ]),
    ]);

    try {
        (new Notifiable)->notify(new TestNotification);
    } catch (CouldNotSendNotification $e) {
        expect($e->getMessage())
            ->toContain('{"id":"","recipients":0,"errors":["All included players are not subscribed"]}');
    }

    Event::assertNotDispatched(OneSignalNotificationNotSent::class);

    Event::assertDispatched(OneSignalNotificationSending::class, static function ($event) {
        return $event->notification instanceof TestNotification;
    });

    Event::assertDispatched(OneSignalNotificationSent::class);

    Event::assertDispatched(OneSignalNotificationErrorsOccurred::class);
});
