<?php

namespace Tnpdigital\Cardinal\Hostfact;

use Illuminate\Support\ServiceProvider;

class HostfactServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hostfact.php' => config_path('hostfact.php')
        ]);
    }

    public function register()
    {
        //
    }
}