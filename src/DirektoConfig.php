<?php

namespace NotificationChannels\Direkto;

class DirektoConfig
{
    /**
     * @var array
     */
    private $config;

    /**
     * DirektoConfig constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the account sid.
     *
     * @return string
     */
    public function getAccountURL()
    {
        return $this->config['account_url'];
    }

}
