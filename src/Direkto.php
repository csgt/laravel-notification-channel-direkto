<?php

namespace NotificationChannels\Direkto;

use NotificationChannels\Direkto\Exceptions\CouldNotSendNotification;
use Direkto\Rest\Client as DirektoService;

class Direkto
{
    /**
     * @var DirektoService
     */
    protected $direktoService;

    /**
     * @var DirektoConfig
     */
    private $config;

    /**
     * Direkto constructor.
     *
     * @param  DirektoService $direktoService
     * @param DirektoConfig   $config
     */
    public function __construct(DirektoService $direktoService, DirektoConfig $config)
    {
        $this->direktoService = $direktoService;
        $this->config = $config;
    }


    /**
     * Send an sms message using the Direkto Service.
     *
     * @param DirektoSmsMessage $message
     * @param string           $to
     * @return \Direkto\Rest\Api\V2010\Account\MessageInstance
     */
    protected function sendSmsMessage(DirektoSmsMessage $message, $to)
    {
        $params = [
            'from' => $this->getFrom($message),
            'body' => trim($message->content),
        ];

        if ($serviceSid = $this->config->getServiceSid()) {
            $params['messagingServiceSid'] = $serviceSid;
        }

        return $this->direktoService->messages->create($to, $params);
    }

    /**
     * Get the from address from message, or config.
     *
     * @param DirektoMessage $message
     * @return string
     * @throws CouldNotSendNotification
     */
    protected function getFrom(DirektoMessage $message)
    {
        if (! $from = $message->getFrom() ?: $this->config->getFrom()) {
            throw CouldNotSendNotification::missingFrom();
        }

        return $from;
    }

    /**
     * Get the alphanumeric sender from config, if one exists.
     *
     * @return string|null
     */
    protected function getAlphanumericSender()
    {
        if ($sender = $this->config->getAlphanumericSender()) {
            return $sender;
        }
    }
}
