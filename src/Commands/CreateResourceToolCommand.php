<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateResourceToolCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:resource-tool";

    protected $description = "Create a new resource tool";

    public function handle()
    {

        $resourceTool = $this->argument('resource-tool');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{resourceTool}}',
            '{{namespace}}',
            '{{uriKey}}'
        ],[
            $this->studly($resourceTool),
            $this->namespace($package),
            Str::kebab($resourceTool)
        ], $this->stub('ResourceTool'));

        $this->createDirIfDoesntExists('ResourceTools', $this->studly($package));
        $this->createDirIfDoesntExists("Assets/js/ResourceTools/{$this->studly($resourceTool)}", $this->studly($package));

        $file = $this->dir($this->studly($package))."/ResourceTools/{$this->studly($resourceTool)}.php";

        $overwrite = true;

        if(file_exists($file)){
            $overwrite = $this->confirm('File already exists, do you want overwrite it?', false);
        }

        if($overwrite) {

            file_put_contents($file, $template);
            file_put_contents($this->dir($this->studly($package)) . "/Assets/js/ResourceTools/{$this->studly($resourceTool)}/Tool.vue", $this->stub('ResourceToolVue'));

            $this->line("Resource tool {$this->studly($resourceTool)} has been created!");

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
            ['resource-tool', InputArgument::REQUIRED, 'Resource tool name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
