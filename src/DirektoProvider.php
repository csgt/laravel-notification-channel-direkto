<?php

namespace NotificationChannels\Direkto;

use Illuminate\Support\ServiceProvider;
use Direkto\Rest\Client as DirektoService;

class DirektoProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(DirektoChannel::class)
            ->needs(Direkto::class)
            ->give(function () {
                return new Direkto(
                    $this->app->make(DirektoService::class),
                    $this->app->make(DirektoConfig::class)
                );
            });

        $this->app->bind(DirektoService::class, function () {
            $config = $this->app['config']['services.direkto'];
            return new DirektoService($config['account_sid'], $config['auth_token']);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind(DirektoConfig::class, function () {
            return new DirektoConfig($this->app['config']['services.direkto']);
        });
    }
}
