<?php

namespace CCast\NovaPackager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class LoaderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
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
