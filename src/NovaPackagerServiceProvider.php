<?php

namespace CCast\NovaPackager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Nova\Nova;
use Symfony\Component\Finder\Finder;

class NovaPackagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . "/../config/nova-packager.php", 'nova-packager');

        $this->publishes([
            __DIR__ . '/../config/nova-packager.php' => config_path('nova-packager.php')
        ], 'nova-packager');

        $this->commands(config('nova-packager.commands'));

        $this->registerResources();
    }

    public function register()
    {
        //
    }

    protected function registerResources()
    {
        $path = base_path(config('nova-packager.path'));

        $namespace = config('nova-packager.namespace');

        if(!is_dir($path)) return;

        foreach((new Finder())->in($path)->directories() as $dir){

            $name = Str::studly($dir->getFilename());
            $provider = "$namespace\\$name\\{$name}ServiceProvider";

            if(class_exists($provider)){
                $this->app->register($provider);
            }
        }
    }
}
