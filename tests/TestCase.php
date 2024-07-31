<?php

declare(strict_types=1);

namespace Macellan\OneSignal\Tests;

use Macellan\OneSignal\OneSignalServiceProvider;

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

    protected function getPackageProviders($app): array
    {
        return [OneSignalServiceProvider::class];
    }
}
