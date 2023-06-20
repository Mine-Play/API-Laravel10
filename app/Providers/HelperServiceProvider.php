<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $files = array_diff( scandir(base_path('app/Helpers')), array('..', '.'));
        foreach ($files as $file){
            require_once base_path('app/Helpers/').$file;
        }
    }
}
