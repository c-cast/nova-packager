<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateServiceProviderCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:service-provider";

    protected $description = "Create a new service provider";

    public function handle()
    {

        $sanitizedName = $this->argument('name') ?
            str_replace(['Service', 'Provider'], ['', ''], $this->studly($this->argument('name')))
            : $this->studly($this->argument('package'));

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        if($sanitizedName == $this->studly($package) && !$this->option('main')){
            $this->warn('You are going to override main module service provider. Action aborted.');
        }else{

            $stub = $this->option('main') ? 'ServiceProvider' : 'CustomServiceProvider';

            $template = str_replace([
                '{{className}}',
                '{{namespace}}',
                '{{uriKey}}'
            ],[
                $sanitizedName,
                $this->namespace($package),
                Str::kebab($this->argument('package'))
            ], $this->stub($stub));


            $file = $this->dir($sanitizedName)."/{$sanitizedName}ServiceProvider.php";

            $overwrite = true;

            if(file_exists($file)){
                $overwrite = $this->confirm('File already exists, do you want overwrite it?', false);
            }

            if($overwrite) {

                file_put_contents($file, $template);

                $this->line("{$sanitizedName}ServiceProvider has been created!");

            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['package', InputArgument::REQUIRED, 'Package name'],
            ['name', InputArgument::OPTIONAL, 'Service Provider name'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['main', null, InputOption::VALUE_OPTIONAL, 'Create Main Service Provider', null]
        ];
    }
}
