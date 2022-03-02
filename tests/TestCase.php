<?php

namespace Macellan\OneSignal\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public string $appId = 'test_app_id';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.onesignal.app_id' => $this->appId,
        ]);
    }

    protected function getPackageProviders($app)
    {
        return ['Macellan\OneSignal\OneSignalServiceProvider'];
    }
}
