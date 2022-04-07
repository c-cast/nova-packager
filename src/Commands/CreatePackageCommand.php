<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class CreatePackageCommand extends Command
{
    use NovaPackageCommand;

    protected $name = "nova-packager:create";

    protected $description = "Create new Package";

    public function handle()
    {
        $name = $this->argument('package');

        $this->generatePackageDirectories();
        $this->generateRouteFile();
        $this->generateAssets();
        $this->generateComposer();

        Artisan::call('nova-packager:service-provider', [
            'package' => $name,
            '--main' => true
        ]);

    }

    /**
     * Create package directories
     * @return void
     */
    protected function generatePackageDirectories()
    {

        $name = $this->argument('package');

        if(!is_dir($this->dir())){
            mkdir($this->dir());
        }

        mkdir($this->dir($this->studly($name)));

        $directories = [
            'Assets',
            'Database',
            'Routes',
            'Assets/js',
            'Assets/js/Fields',
            'Assets/js/Filters',
            'Assets/js/Cards',
            'Assets/js/ResourceTools',
            'Assets/js/Tools',
            'Assets/sass',
            'Assets/views',
            'Database/migrations',
            'Database/seeds',
        ];

        foreach ($directories as $directory){
            $this->createDirIfDoesntExists($directory, $this->studly($name));
        }

        $this->line('Directories have been created');
    }

    /**
     * Generate api routes file
     * @return void
     */
    protected function generateRouteFile()
    {

        $uriKey = Str::kebab($this->argument('package'));

        $template = str_replace([
            '{{uriKey}}'
        ],[
            $uriKey
        ], $this->stub('ApiRoutes'));

        file_put_contents($this->dir($this->studly($this->argument('package')))."/Routes/api.php", $template);

        $this->line("Api routes file has been created!");

    }

    /**
     * Generate composer file
     * @return void
     */
    protected function generateComposer()
    {
        $name = $this->argument('package');

        $template = str_replace([
            '{{package}}',
            '{{serviceProvider}}'
        ],[
            $name,
            "{$this->namespace($name)}\\{$this->studly($name)}\\{$this->studly($name)}ServiceProvider"
        ], $this->stub('Composer'));

        file_put_contents($this->dir($this->studly($name))."/composer.json", $template);

        $this->line("Composer has been created!");
    }

    /**
     * Generate assets files
     * @return void
     */
    protected function generateAssets()
    {
        $name = $this->argument('package');

        $template = str_replace([
            '{{uriKey}}'
        ], [
            Str::kebab($name)
        ], $this->stub('Mix'));

        $uriKey = Str::kebab($name);

        file_put_contents($this->dir($this->studly($name))."/Assets/js/$uriKey.js", $this->stub('Js'));
        file_put_contents($this->dir($this->studly($name))."/Assets/sass/$uriKey.scss", $this->stub('Css'));
        file_put_contents($this->dir($this->studly($name)) . "/webpack.mix.js", $template);
        file_put_contents($this->dir($this->studly($name)) . "/package.json", $this->stub('Package'));
        file_put_contents($this->dir($this->studly($name))."/.gitignore", $this->stub('Git'));

        $this->line("Assets have been created!");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['package', InputArgument::REQUIRED, 'Package name']
        ];
    }
}
