<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateToolCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:tool";

    protected $description = "Create a new tool";

    public function handle()
    {

        $tool = $this->argument('tool');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{tool}}',
            '{{namespace}}',
            '{{uriKey}}',
            '{{toolTitle}}',
            '{{toolPath}}'
        ],[
            $this->studly($tool),
            $this->namespace($package),
            Str::kebab($tool),
            Str::replace('_', ' ', Str::snake($tool)),
            Str::kebab($tool)
        ], $this->stub('Tool'));

        $this->createDirIfDoesntExists('Tools', $this->studly($package));
        $this->createDirIfDoesntExists("Assets/js/Tools/{$this->studly($tool)}", $this->studly($package));

        $file = $this->dir($this->studly($package))."/Tools/{$this->studly($tool)}.php";

        $overwrite = true;

        if(file_exists($file)){
            $overwrite = $this->confirm('File already exists, do you want overwrite it?', false);
        }

        if($overwrite) {

            file_put_contents($file, $template);
            file_put_contents($this->dir($this->studly($package)) . "/Assets/js/Tools/{$this->studly($tool)}/Tool.vue", $this->stub('ToolVue'));

            $this->line("Custom filter {$this->studly($tool)} has been created!");

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
            ['tool', InputArgument::REQUIRED, 'Tool name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
