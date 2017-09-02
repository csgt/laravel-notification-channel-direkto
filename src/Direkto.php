<?php

namespace NotificationChannels\Direkto;

use NotificationChannels\Direkto\Exceptions\CouldNotSendNotification;
use GuzzleHttp\Client as DirektoService;

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
     * @param DirektoConfig   $config
     */
    public function __construct(DirektoConfig $config)
    {
        $this->direktoService = new DirektoService();
        $this->config = $config;
    }


    /**
     * Send an sms message using the Direkto Service.
     *
     * @param DirektoSmsMessage $message
     * @param string           $to
     * @return \Direkto\MessageInstance
     */
    protected function sendSmsMessage(DirektoMessage $message, $to)
    {
        $params = [
            'telefono' => $to,
            'texto'    => trim($message->content),
        ];

        if (!$serviceURL = $this->config->getAccountURL()) {
            throw CouldNotSendNotification::missingURL();
        }
        return $this->direktoService->get($serviceURL . http_build_query($params));
    }

}
