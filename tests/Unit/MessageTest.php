<?php

declare(strict_types=1);

use Macellan\OneSignal\OneSignalMessage;

test('create', function () {
    $message = OneSignalMessage::create($body = 'body')
        ->setSubject($subject = 'Subject')
        ->setData($data = [
            'en' => 'Data',
            'tr' => 'Data',
        ])
        ->setIcon($icon = 'test_icon.png');

    expect($message)
        ->getBody()
        ->toBe(['en' => $body])
        ->getHeadings()
        ->toBe(['en' => $subject])
        ->getData()
        ->not
        ->toBeNull()
        ->toEqual($data)
        ->getIcon()
        ->not
        ->toBeNull()
        ->toEqual($icon);
});

test('create multiple lang', function () {
    $message = OneSignalMessage::create($body = ['en' => 'Body', 'tr' => 'Body'])
        ->setSubject($subject = [
            'en' => 'Subject',
            'tr' => 'Subject',
        ])
        ->setData($data = [
            'en' => 'Data',
            'tr' => 'Data',
        ]);

    expect($message)
        ->getBody()
        ->toEqual($body)
        ->getHeadings()
        ->toEqual($subject)
        ->getData()
        ->not
        ->toBeNull()
        ->toEqual($data);
});

test('change app id', function () {

    $message = OneSignalMessage::create($body = 'body')->setAppId($appId = 'other_app_id');

    expect($message)
        ->getBody()
        ->toBe(['en' => $body])
        ->getAppId()
        ->not
        ->toBeNull()
        ->toBe($appId);
});
