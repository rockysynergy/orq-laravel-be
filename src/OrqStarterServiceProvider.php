<?php

namespace Orq\Laravel\Starter;

use Illuminate\Support\ServiceProvider;

class OrqStarterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../configs/site.config.php', 'site');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../configs/site.config.php' => config_path('site.php'),
        ], 'configs');
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/OrqStarter')
        ], 'assets');

        $this->loadMigrationsFrom(__DIR__.'/../migrations');

        $this->loadViewsFrom(__DIR__.'/../views', 'OrqStarter');
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/OrqStarter'),
        ], 'views');
    }
}
