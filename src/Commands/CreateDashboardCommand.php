<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateDashboardCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:dashboard";

    protected $description = "Create a new dashboard";

    public function handle()
    {

        $dashboard = $this->argument('dashboard');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{dashboard}}',
            '{{namespace}}',
            '{{uriKey}}'
        ],[
            $this->studly($dashboard),
            $this->namespace($package),
            Str::kebab($dashboard)
        ], $this->stub('Dashboard'));

        $this->createDirIfDoesntExists('Dashboards', $this->studly($package));

        $file = $this->dir($this->studly($package))."/Dashboards/{$this->studly($dashboard)}.php";

        $overwrite = true;

        if(file_exists($file)){
            $overwrite = $this->confirm('File already exists, do you want overwrite it?', false);
        }

        if($overwrite) {

            file_put_contents($file, $template);

            $this->line("Dashboard {$this->studly($dashboard)} has been created!");

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
            ['dashboard', InputArgument::REQUIRED, 'Dashboard name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
