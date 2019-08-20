<?php
namespace NotificationChannels\Direkto;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\Direkto\Exceptions\CouldNotSendNotification;

class DirektoChannel
{
    /**
     * @var Direkto
     */
    protected $direkto;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * DirektoChannel constructor.
     *
     * @param Direkto     $direkto
     * @param Dispatcher $events
     */
    public function __construct(Direkto $direkto, Dispatcher $events)
    {
        $this->direkto = $direkto;
        $this->events  = $events;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed                                  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $to      = $this->getTo($notifiable);
            $message = $notification->toDirekto($notifiable);
            if (is_string($message)) {
                $message = new DirektoMessage($message);
            }
            if (!$message instanceof DirektoMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            return $this->direkto->sendMessage($message, $to);
        } catch (Exception $exception) {
            event(
                new NotificationFailed($notifiable, $notification, 'direkto', ['message' => $exception->getMessage()])
            );
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo($notifiable)
    {
        if ($notifiable->routeNotificationFor('direkto')) {
            return $notifiable->routeNotificationFor('direkto');
        }
        if (isset($notifiable->celular)) {
            return $notifiable->celular;
        }
        throw CouldNotSendNotification::invalidReceiver();
    }

    /**
     * Get the alphanumeric sender.
     *
     * @param $notifiable
     * @return mixed|null
     * @throws CouldNotSendNotification
     */
    protected function canReceiveAlphanumericSender($notifiable)
    {
        return false;
    }
}
