<?php

declare(strict_types=1);

namespace Macellan\OneSignal;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class OneSignalServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('onesignal', function () {
                if (! $config = config('services.onesignal')) {
                    throw new \RuntimeException('OneSignal configuration not found.');
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
