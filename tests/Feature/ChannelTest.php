<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Macellan\OneSignal\Exceptions\CouldNotSendNotification;
use Macellan\OneSignal\OneSignalChannel;
use Macellan\OneSignal\OneSignalMessage;
use Macellan\OneSignal\Tests\Fixtures\Notifiable;
use Macellan\OneSignal\Tests\Fixtures\Notifications\TestIconNotification;
use Macellan\OneSignal\Tests\Fixtures\Notifications\TestNotification;
use Macellan\OneSignal\Tests\Fixtures\Notifications\TestNotificationByText;
use Macellan\OneSignal\Tests\Fixtures\Notifications\TestOtherAppIdNotification;
use Macellan\OneSignal\Tests\Fixtures\WrongNotifiable;
use Mockery\MockInterface;

test('throw exception when services.onesignal is not configured', function () {
    config(['services.onesignal' => null]);

    (new Notifiable)->notify(new TestNotification);

})->throws(\RuntimeException::class, 'OneSignal configuration not found.');

test('can send a notification', function () {
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
});

test('throws an exception service bad request', function () {
    Http::fake([
        'api/v1/notifications' => Http::response([
            'errors' => [
                'include_player_ids must be an array',
            ],
        ], 400),
    ]);

    $this->expectException(\Illuminate\Http\Client\RequestException::class);

    (new Notifiable)->notify(new TestNotification);
});

test('not success notification', function () {
    Http::fake([
        'api/v1/notifications' => Http::response([
            'id' => '',
            'recipients' => 0,
            'errors' => [
                'All included players are not subscribed',
            ],
        ]),
    ]);

    $this->expectException(CouldNotSendNotification::class);

    (new Notifiable)->notify(new TestNotification);
});

test('change app id', function () {
    Http::fake([
        'api/v1/notifications' => Http::response([
            'id' => '931082f5-e442-42b1-a951-19e7e45dee39',
            'recipients' => 1,
            'external_id' => null,
        ]),
    ]);

    (new Notifiable)->notify(new TestOtherAppIdNotification);

    Http::assertSent(static function (Request $request) {
        return $request['app_id'] === 'other_app_id';
    });
});

test('icon', function () {
    Http::fake([
        'api/v1/notifications' => Http::response([
            'id' => '931082f5-e442-42b1-a951-19e7e45dee39',
            'recipients' => 1,
            'external_id' => null,
        ]),
    ]);

    (new Notifiable)->notify(new TestIconNotification);

    Http::assertSent(static function (Request $request) {
        return $request['huawei_large_icon'] === 'test-icon.jpg' &&
            $request['large_icon'] === 'test-icon.jpg' &&
            $request['ios_attachments'] === ['icon' => 'test-icon.jpg'];
    });
});

test('web url', function () {
    Http::fake([
        'api/v1/notifications' => Http::response([
            'id' => '931082f5-e442-42b1-a951-19e7e45dee39',
            'recipients' => 1,
            'external_id' => null,
        ]),
    ]);

    $webUrl = 'https://macellan.net/';
    $notification = $this->partialMock(TestNotification::class, function (MockInterface $mock) use ($webUrl): void {
        $mock->makePartial()
            ->shouldReceive('toOneSignal')
            ->andReturn((new OneSignalMessage)->setWebUrl($webUrl));
    });

    (new Notifiable)->notify($notification);

    Http::assertSent(static function (Request $request) use ($webUrl) {
        return $request['web_url'] === $webUrl;
    });
});

test('text notification', function () {
    Http::fake([
        'api/v1/notifications' => Http::response([
            'id' => '931082f5-e442-42b1-a951-19e7e45dee39',
            'recipients' => 1,
            'external_id' => null,
        ]),
    ]);

    (new Notifiable)->notify(new TestNotificationByText);

    Http::assertSent(static function (Request $request): bool {
        return $request['contents'] === ['en' => 'My notification'];
    });
});

test('user ids are empty', function () {
    Http::fake([
        'api/v1/notifications' => Http::response(),
    ]);

    (new WrongNotifiable)->notify(new TestNotificationByText);

    Http::assertNothingSent();
});
