<?php

namespace CCast\NovaPackager\Commands;

use Illuminate\Support\Str;

trait NovaPackageCommand
{

    /**
     * Return full path
     *
     * @param $package
     * @return string
     */
    protected function dir($package = null): string
    {
        if(!$package){
            return config('nova-packager.path');
        }

        return config('nova-packager.path')."/".$this->studly($package);
    }


    /**
     * @param string $dir
     * @param string $packageName
     * @return void
     */
    protected function createDirIfDoesntExists(string $dir, string $packageName)
    {
        $directory = "{$this->dir($packageName)}/$dir";

        if(!is_dir($directory)){
            mkdir($directory);
        }
    }

    /**
     * Return full namespace
     * @param string $module
     * @return string
     */
    protected function namespace(string $module): string
    {
        $namespace = config('nova-packager.namespace');

        $studlyModule = $this->studly($module);

        return "$namespace\\$studlyModule";
    }

    /**
     * Return string in studly format
     * @param string $string
     * @return string
     */
    protected function studly(string $string): string
    {
        return Str::studly($string);
    }

    /**
     * Retrieve Stub File
     * @param $name
     * @return false|string
     */
    protected function stub($name)
    {
        $stub = ucwords($name);
        return file_get_contents( __DIR__ . "/../../Stubs/$stub.stub");
    }

    /**
     * Return if package exists
     * @param $package
     * @return bool
     */
    protected function packageExists($package)
    {
        return is_dir($this->dir($package));
    }
}
