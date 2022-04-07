<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateActionCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:action";

    protected $description = "Create a new action";

    public function handle()
    {

        $action = $this->argument('action');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{action}}',
            '{{namespace}}',
        ],[
            $this->studly($action),
            $this->namespace($package),
        ], $this->stub('Action'));

        $this->createDirIfDoesntExists('Actions', $this->studly($package));

        file_put_contents($this->dir($this->studly($package))."/Actions/{$this->studly($action)}.php", $template);

        $this->line("Action {$this->studly($action)} has been created!");

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['action', InputArgument::REQUIRED, 'Action name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
