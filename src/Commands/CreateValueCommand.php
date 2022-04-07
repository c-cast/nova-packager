<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateValueCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:value";

    protected $description = "Create a new value metric";

    public function handle()
    {

        $metric = $this->argument('metric');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{metric}}',
            '{{namespace}}',
            '{{uriKey}}'
        ],[
            $this->studly($metric),
            $this->namespace($package),
            Str::kebab($metric)
        ], $this->stub('Value'));

        $this->createDirIfDoesntExists('Metrics', $this->studly($package));

        $file = $this->dir($this->studly($package))."/Metrics/{$this->studly($metric)}.php";

        $overwrite = true;

        if(file_exists($file)){
            $overwrite = $this->confirm('File already exists, do you want overwrite it?', false);
        }

        if($overwrite) {

            file_put_contents($file, $template);

            $this->line("Value metric {$this->studly($metric)} has been created!");

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
            ['metric', InputArgument::REQUIRED, 'Value metric name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
