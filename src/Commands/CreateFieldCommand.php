<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateFieldCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:field";

    protected $description = "Create a new field";

    public function handle()
    {

        $field = $this->argument('field');

        $package = $this->argument('package');

        if(!$this->packageExists($package)){
            $this->error("Package doesn't exists!");
            return;
        }

        $template = str_replace([
            '{{field}}',
            '{{namespace}}',
            '{{uriKey}}'
        ],[
            $this->studly($field),
            $this->namespace($package),
            Str::kebab($field)
        ], $this->stub('Field'));

        $this->createDirIfDoesntExists('Fields', $this->studly($package));
        $this->createDirIfDoesntExists("Assets/js/Fields/{$this->studly($field)}", $this->studly($package));

        $file = $this->dir($this->studly($package))."/Fields/{$this->studly($field)}.php";

        $overwrite = true;

        if(file_exists($file)){
            $overwrite = $this->confirm('File already exists, do you want overwrite it?', false);
        }

        if($overwrite) {

            file_put_contents($file, $template);
            file_put_contents($this->dir($this->studly($package)) . "/Assets/js/Fields/{$this->studly($field)}/DetailField.vue", $this->stub('DetailField'));
            file_put_contents($this->dir($this->studly($package)) . "/Assets/js/Fields/{$this->studly($field)}/FormField.vue", $this->stub('FormField'));
            file_put_contents($this->dir($this->studly($package)) . "/Assets/js/Fields/{$this->studly($field)}/IndexField.vue", $this->stub('IndexField'));

            $this->line("Field {$this->studly($field)} has been created!");

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
            ['field', InputArgument::REQUIRED, 'Field name'],
            ['package', InputArgument::REQUIRED, 'Package name'],
        ];
    }

}
