<?php

namespace Macellan\OneSignal;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class OneSignalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @noinspection PhpUndefinedFunctionInspection
     * @throws \Exception
     */
    public function boot()
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('onesignal', function () {
                if (! $config = config('services.onesignal')) {
                    throw new \Exception('OneSignal configuration not found.');
                }

                return new OneSignalChannel($config['app_id']);
            });
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}