<?php

namespace Kitano\Aktiv8me;

use Illuminate\Support\ServiceProvider;
use Kitano\Aktiv8me\Console\MakeEmailVerification;

class Aktiv8meServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/lang/', 'aktiv8me');

        $this->publishes([
            __DIR__.'/lang/en/aktiv8me.php' => resource_path('lang/en/aktiv8me.php'),
            __DIR__.'/config/aktiv8me.php' => config_path('aktiv8me.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeEmailVerification::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/aktiv8me.php',
            'aktiv8me'
        );
    }
}
