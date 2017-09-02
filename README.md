# Twilio notifications channel for Laravel 5.3+

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/twilio.svg?style=flat-square)](https://packagist.org/packages/csgt/notification-channel-direkto)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/twilio/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/twilio)
[![StyleCI](https://styleci.io/repos/65543339/shield)](https://styleci.io/repos/65543339)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/twilio.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/twilio)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/twilio/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/twilio/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/twilio.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/twilio)

This package makes it easy to send [Direkto notifications] with Laravel 5.4.

## Contents
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require csgt/notification-channel-direkto
```

You must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Direkto\DirektoProvider::class,
],
```

### Setting up your Direkto account

Add your Direkto Account SID, Auth Token, and From Number (optional) to your `config/services.php`:

```php
// config/services.php
...
'direkto' => [
    'account_sid' => env('DIREKTO_ACCOUNT_SID'),
    'auth_token' => env('DIREKTO_AUTH_TOKEN'),
    'from' => env('DIREKTO_FROM'), // optional
],
...
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Direkto\DirektoChannel;
use NotificationChannels\Direkto\DirektoSmsMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    public function via($notifiable)
    {
        return [DirektoChannel::class];
    }

    public function toDirekto($notifiable)
    {
        return (new DirektoSmsMessage())
            ->content("Your {$notifiable->service} account was approved!");
    }
}
```

In order to let your Notification know which phone are you sending/calling to, the channel will look for the `phone_number` attribute of the Notifiable model. If you want to override this behaviour, add the `routeNotificationForDirekto` method to your Notifiable model.

```php
public function routeNotificationForDirekto()
{
    return '+1234567890';
}
```

### Available Message methods

#### DirektoSmsMessage

- `from('')`: Accepts a phone to use as the notification sender.
- `content('')`: Accepts a string value for the notification body.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email jgalindo@cs.com.gt instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [CS](https://github.com/csgt)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
