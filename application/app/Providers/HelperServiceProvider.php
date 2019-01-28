<?php

namespace App\Providers;

use File;
use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Autoload all the helper files.
        $files = File::allFiles(base_path('helpers'));
    
        foreach ($files as $file) {
            $fileName = $file->getRelativePathName();
            require base_path("helpers/{$fileName}");
        }
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
