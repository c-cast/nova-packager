<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\ActionResource;
use Laravel\Nova\Resource;
use Symfony\Component\Finder\Finder;

class ResourceListCommand extends Command
{

    protected $name = "nova-packager:resource-list";

    protected $description = "Get list of active resources";

    public function handle()
    {
        $packagesDirectory = config('nova-packager.path');

        if(!is_dir($packagesDirectory)) return;

        $resources = [];

        $OS_Separator = PHP_OS === 'Linux' ? '/' : '\\';

        foreach( new \DirectoryIterator($packagesDirectory) as $dir )
        {
            if(!$dir->isDot()){
                $namespace =  config('nova-packager.namespace') . "\\$dir";

                foreach ((new Finder)->in($dir->getRealPath())->path('Resources')->files() as $resource) {
                    $resource = str_replace(
                        '.php',
                        '',
                        $namespace."\\Resources\\".Str::afterLast($resource, $OS_Separator)
                    );

                    if (is_subclass_of($resource, Resource::class) &&
                        ! (new \ReflectionClass($resource))->isAbstract() &&
                        ! (is_subclass_of($resource, ActionResource::class))) {
                        $resources[] = [
                            'Namespace' => $namespace,
                            'Resource' => last(explode("\\", $resource)),
                        ];
                    }
                }
            }

        }

        $this->table(['Namespace', 'Resource'], (array) $resources);
    }
}
