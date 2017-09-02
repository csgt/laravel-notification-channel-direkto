<?php

namespace NotificationChannels\Direkto;

use Illuminate\Support\ServiceProvider;

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
                    $this->app->make(DirektoConfig::class)
                );
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
