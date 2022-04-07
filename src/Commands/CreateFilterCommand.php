<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateFilterCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:filter";

    protected $description = "Create a new filter";

    public function handle()
    {

        $filter = $this->argument('filter');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{filter}}',
            '{{namespace}}',
        ],[
            $this->studly($filter),
            $this->namespace($package),
        ], $this->stub('Filter'));

        $this->createDirIfDoesntExists('Filters', $this->studly($package));

        $file = $this->dir($this->studly($package))."/Filters/{$this->studly($filter)}.php";

        $overwrite = true;

        if(file_exists($file)){
            $overwrite = $this->confirm('File already exists, do you want overwrite it?', false);
        }

        if($overwrite) {

            file_put_contents($file, $template);

            $this->line("Filter {$this->studly($filter)} has been created!");

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
            ['filter', InputArgument::REQUIRED, 'Filter name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
