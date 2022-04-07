<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateLensCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:lens";

    protected $description = "Create a new lens";

    public function handle()
    {

        $lens = $this->argument('lens');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{lens}}',
            '{{namespace}}',
            '{{uriKey}}'
        ],[
            $this->studly($lens),
            $this->namespace($package),
            Str::kebab($lens)
        ], $this->stub('Lens'));

        $this->createDirIfDoesntExists('Lenses', $this->studly($package));

        $file = $this->dir($this->studly($package))."/Lenses/{$this->studly($lens)}.php";

        $overwrite = true;

        if(file_exists($file)){
            $overwrite = $this->confirm('File already exists, do you want overwrite it?', false);
        }

        if($overwrite) {

            file_put_contents($file, $template);

            $this->line("Lens {$this->studly($lens)} has been created!");

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
            ['lens', InputArgument::REQUIRED, 'Lens name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
