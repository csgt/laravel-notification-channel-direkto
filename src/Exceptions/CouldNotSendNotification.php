<?php

namespace NotificationChannels\Direkto\Exceptions;

use NotificationChannels\Direkto\SmsMessage;

class CouldNotSendNotification extends \Exception
{
    /**
     * @param mixed $message
     *
     * @return static
     */
    public static function invalidMessageObject($message)
    {
        $className = get_class($message) ?: 'Unknown';

        return new static(
            "Notification was not sent. Message object class `{$className}` is invalid. It should
            be ".SmsMessage::class);
    }

    /**
     * @return static
     */
    public static function missingFrom()
    {
        return new static('Notification was not sent. Missing `from` number.');
    }

    /**
     * @return static
     */
    public static function missingURL()
    {
        return new static('Notification was not sent. Missing `URL` parameter.');
    }

    /**
     * @return static
     */
    public static function invalidReceiver()
    {
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForTwilio
            method or a phone_number attribute to your notifiable.'
        );
    }

    public static function missingAlphaNumericSender()
    {
        return new static(
            'Notification was not sent. Missing `alphanumeric_sender` in config'
        );
    }
}
