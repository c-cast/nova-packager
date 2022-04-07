<?php

namespace CCast\NovaPackager;

use Illuminate\Support\ServiceProvider;

class NovaPackagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . "/../config/nova-packager.php", 'nova-packager');

        $this->publishes([
            __DIR__ . '/../config/nova-packager.php' => config_path('nova-packager.php')
        ], 'nova-packager');

        $this->commands(config('nova-packager.commands'));
    }

    public function register()
    {
        $this->app->register(LoaderServiceProvider::class);
    }
}
