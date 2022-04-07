<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateResourceCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:resource";

    protected $description = "Create a new resource";

    public function handle()
    {

        $resource = $this->argument('resource');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{resourceName}}',
            '{{namespace}}',
            '{{model}}',
            '{{group}}'
        ],[
            $this->studly($resource),
            $this->namespace($package),
            $this->namespace($package)."\\Models\\".$this->studly($resource),
            Str::upper($package)
        ], $this->stub('Resource'));

        $this->createDirIfDoesntExists('Resources', $this->studly($package));

        $file = $this->dir($this->studly($package))."/Resources/{$this->studly($resource)}.php";

        $overwrite = true;

        if(file_exists($file)){
            $overwrite = $this->confirm('File already exists, do you want overwrite it?', false);
        }

        if($overwrite){
            file_put_contents($file, $template);

            $this->line("Resource {$this->studly($resource)} has been created!");

            $relatedModel = $this->confirm("Do you want to create related model?", true);

            if($relatedModel){
                $this->call(CreateModelCommand::class, ['package' => $package, 'model' => $resource]);
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
            ['resource', InputArgument::REQUIRED, 'Resource name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
