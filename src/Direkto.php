<?php

namespace NotificationChannels\Direkto;

use NotificationChannels\Direkto\Exceptions\CouldNotSendNotification;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use Log;

class Direkto
{
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
        $this->config = $config;
    }


    /**
     * Send an sms message using the Direkto Service.
     *
     * @param DirektoMessage $message
     * @param string           $to
     * @return \Direkto\MessageInstance
     */
    public function sendMessage(DirektoMessage $message, $to)
    {
        $params = [
            'telefono' => $to,
            'texto'    => trim($message->content),
        ];

        if (!$serviceURL = $this->config->getAccountURL()) {
            throw CouldNotSendNotification::missingURL();
        }
        $cliente = new Client;
        try {
            $response = $cliente->request('GET', $serviceURL, ['query' => $params,  'timeout' => 25, 'verify' => false]);
            $html     = (string)$response->getBody();
        }
        catch (RequestException $e) {
            if ($e->hasResponse()) {
                throw CouldNotSendNotification::errorSending(Psr7\str($e->getResponse()));
            }
            throw CouldNotSendNotification::errorSending($e->getMessage());
        }
        return $response;
    }

}
