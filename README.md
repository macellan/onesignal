# OneSignal Notifications Channel for Laravel  

[![Latest Version on Packagist](https://img.shields.io/packagist/v/macellan/onesignal.svg?style=flat-square)](https://packagist.org/packages/macellan/onesignal)
[![Build Status](https://scrutinizer-ci.com/g/macellan/onesignal/badges/build.png?b=main)](https://scrutinizer-ci.com/g/macellan/onesignal/build-status/main)
[![StyleCI](https://github.styleci.io/repos/463069750/shield?branch=main)](https://github.styleci.io/repos/463069750?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/macellan/onesignal/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/macellan/onesignal/?branch=main)
[![Total Downloads](https://img.shields.io/packagist/dt/macellan/onesignal.svg?style=flat-square)](https://packagist.org/packages/macellan/onesignal)  


This package makes it easy to send push notifications using [OneSignal](https://onesignal.com/) with Laravel 8.0+ and 9.0+  

This plugin sends notifications only with OneSignal Player ID.

## Installation

You can install this package via composer:

``` bash
composer require macellan/onesignal
```

### Settings

Add your OneSignal appId to your config/services.php:

```php
// config/services.php
...
    'onesignal' => [
        'app_id' => env('ONESIGNAL_APP_ID', ''),
    ],
...
```

## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use Macellan\OneSignal\OneSignalMessage;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return ['onesignal'];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject('Awesome App')
            ->setBody('Your account was approved!');  
    }
}
```

You can change appId of a specific notification, just add the setAppId() method

```php
   public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setAppId('Other AppId')
    }
```

In your notifiable model, make sure to include a routeNotificationForOneSignal() method.

```php
public function routeNotificationForOneSignal()
{
    return $this->player_id;
}
```


### On-Demand Notifications

Sometimes you may need to send a notification to someone who is not stored as a "user" of your application. Using the Notification::route method, you may specify ad-hoc notification routing information before sending the notification:

```php
Notification::route('onesignal', 'player_id')  
            ->notify(new AccountApproved());
```

## Credits

- [Fatih Aytekin](https://github.com/faytekin)

