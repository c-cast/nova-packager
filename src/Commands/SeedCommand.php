<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class SeedCommand extends Command
{
    use NovaPackageCommand;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'nova-packager:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run package seeder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $package = $this->argument('package');

        $seeder = "{$this->namespace($package)}\\Database\\Seeders\\{$this->studly($package)}DatabaseSeeder";

        if(class_exists($seeder)){
            $this->call('db:seed', ['--class' => $seeder]);
            $this->info("{$this->studly($package)} : Seeding done");
        }else{
            $this->error("Error during seeding. Class {$seeder} doesn't exists.");
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
            ['package', InputArgument::REQUIRED, "Package name"],
        ];
    }

}
