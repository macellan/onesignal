<?php

namespace Macellan\OneSignal\Tests;

use Macellan\OneSignal\OneSignalMessage;

class MessageTest extends TestCase
{
    public function test_create()
    {
        $body = 'body';

        $subject = 'Subject';

        $data = [
            'en' => 'Data',
            'tr' => 'Data',
        ];

        $icon = 'test_icon.png';

        $message = OneSignalMessage::create($body)
            ->setSubject($subject)
            ->setData($data)
            ->setIcon($icon);

        $this->assertEquals(['en' => $body], $message->getBody());
        $this->assertEquals(['en' => $subject], $message->getHeadings());
        $this->assertEquals($data, $message->getData());
        $this->assertEquals($icon, $message->getIcon());
    }

    public function test_create_multiple_lang()
    {
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

        $message = OneSignalMessage::create($body)->setSubject($subject)->setData($data);

        $this->assertEquals($body, $message->getBody());
        $this->assertEquals($subject, $message->getHeadings());
        $this->assertEquals($data, $message->getData());
    }

    public function test_change_app_id()
    {
        $body = 'body';

        $appId = 'other_app_id';

        $message = OneSignalMessage::create($body)->setAppId($appId);

        $this->assertEquals(['en' => $body], $message->getBody());
        $this->assertEquals($appId, $message->getAppId());
    }
}
