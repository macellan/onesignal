<?php

namespace Macellan\OneSignal\Tests;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Macellan\OneSignal\Exceptions\CouldNotSendNotification;
use Macellan\OneSignal\OneSignalChannel;
use Macellan\OneSignal\Tests\Notifications\TestOtherAppIdNotification;
use Macellan\OneSignal\Tests\Notifications\TestNotification;

class ChannelTest extends TestCase
{
    public function test_can_send_a_notification()
    {
        Http::fake([
            'api/v1/notifications' => Http::response([
                "id" => "931082f5-e442-42b1-a951-19e7e45dee39",
                "recipients" => 1,
                "external_id" => null,
            ]),
        ]);

        (new Notifiable)->notify(new TestNotification());

        Http::assertSent(function (Request $request) {
            return $request->url() == OneSignalChannel::ENDPOINT &&
                $request['app_id'] == $this->appId &&
                $request['include_player_ids'] == ['player_id'] &&
                $request['headings'] == ['en' => 'Subject'] &&
                $request['contents'] == ['en' => 'Body'] &&
                $request['data'] == null;
        });
    }

    public function test_throws_an_exception_service_bad_request()
    {
        Http::fake([
            'api/v1/notifications' => Http::response([
                "errors" => [
                    'include_player_ids must be an array'
                ],
            ], 400),
        ]);

        $this->expectException(\Illuminate\Http\Client\RequestException::class);

        (new Notifiable)->notify(new TestNotification());
    }

    public function test_not_success_notification()
    {
        Http::fake([
            'api/v1/notifications' => Http::response([
                "id" => "",
                "recipients" => 0,
                "errors" => [
                    "All included players are not subscribed"
                ]
            ]),
        ]);

        $this->expectException(CouldNotSendNotification::class);

        (new Notifiable)->notify(new TestNotification());
    }

    public function test_change_app_id()
    {
        Http::fake([
            'api/v1/notifications' => Http::response([
                "id" => "931082f5-e442-42b1-a951-19e7e45dee39",
                "recipients" => 1,
                "external_id" => null,
            ]),
        ]);

        (new Notifiable)->notify(new TestOtherAppIdNotification());

        Http::assertSent(function (Request $request) {
            return $request['app_id'] == 'other_app_id';
        });
    }
}
