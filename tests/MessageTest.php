<?php

namespace Macellan\OneSignal\Tests;

use Macellan\OneSignal\OneSignalMessage;

class MessageTest extends TestCase
{
    public function test_create()
    {
        $appId = 'app_id';

        $body = 'body';

        $subject = 'Subject';

        $data = [
            'en' => 'Data',
            'tr' => 'Data',
        ];

        $message = OneSignalMessage::create($body)->setAppId($appId)->setSubject($subject)->setData($data);

        $this->assertEquals($appId, $message->getAppId());
        $this->assertEquals(['en' => $body], $message->getBody());
        $this->assertEquals(['en' => $subject], $message->getHeadings());
        $this->assertEquals($data, $message->getData());
    }

    public function test_create_multiple_lang()
    {
        $appId = 'app_id';

        $body = [
            'en' => 'Body',
            'tr' => 'Body',
        ];

        $subject = [
            'en' => 'Subject',
            'tr' => 'Subject',
        ];

        $data = [
            'en' => 'Data',
            'tr' => 'Data',
        ];

        $message = OneSignalMessage::create($body)->setAppId($appId)->setSubject($subject)->setData($data);

        $this->assertEquals($appId, $message->getAppId());
        $this->assertEquals($body, $message->getBody());
        $this->assertEquals($subject, $message->getHeadings());
        $this->assertEquals($data, $message->getData());
    }
}
