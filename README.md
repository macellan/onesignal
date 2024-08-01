# OneSignal Notifications Channel for Laravel  

![Tests](https://github.com/macellan/onesignal/actions/workflows/tests.yml/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/macellan/onesignal/v/stable)](https://packagist.org/packages/macellan/onesignal)
[![Total Downloads](https://poser.pugx.org/macellan/onesignal/downloads)](https://packagist.org/packages/macellan/onesignal)


This package makes it easy to send push notifications using [OneSignal](https://onesignal.com/) with Laravel 9.0 and 10.0, 11.0  

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

