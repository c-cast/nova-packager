<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateMigrationCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:migration";

    protected $description = "Create a new migration";

    public function handle()
    {

        $migration = $this->argument('migration');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{migration}}'
        ],[
            $this->studly($migration)
        ], $this->stub('Migration'));

        $uniqueFileName = date('Y_m_d')."_".date('his')."_".Str::snake($migration);

        $this->createDirIfDoesntExists('Database/migrations', $this->studly($package));

        file_put_contents($this->dir($this->studly($package))."/Database/migrations/$uniqueFileName.php", $template);

        $this->line("Migration $uniqueFileName has been created!");

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['migration', InputArgument::REQUIRED, 'Migration name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
